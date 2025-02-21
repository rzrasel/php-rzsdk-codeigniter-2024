<?php
namespace App\DatabaseSchema\Helper\Data\Finder;
?>
<?php
class DatabaseSchemaDataFinder {

    public function setSchemas(array $schemas): void {
        $this->schemas = $schemas;
    }

    /*private $databaseSchemas;

    public function __construct(array $databaseSchemas) {
        $this->databaseSchemas = $databaseSchemas;
    }

    public function getSchemaName(string $id): ?array {
        foreach($this->databaseSchemas as $schemaIndex => $schema) {
            if($schema->id === $id) {
                return [
                    "index" => ["schema" => $schemaIndex],
                    "data" => $schema->schemaName,
                ];
            }
        }
        return null;
    }

    public function getTableName(string $id): ?array {
        foreach($this->databaseSchemas as $schemaIndex => $schema) {
            foreach($schema->tableDataList as $tableIndex => $table) {
                if($table->id === $id) {
                    return [
                        "index" => [
                            "schema" => $schemaIndex,
                            "table" => $tableIndex,
                        ],
                        "data" => $table->tableName,
                    ];
                }
            }
        }
        return null;
    }

    public function getColumnName(string $id): ?array {
        foreach($this->databaseSchemas as $schemaIndex => $schema) {
            foreach($schema->tableDataList as $tableIndex => $table) {
                foreach($table->columnDataList as $columnIndex => $column) {
                    if($column->id === $id) {
                        return [
                            "index" => [
                                "schema" => $schemaIndex,
                                "table" => $tableIndex,
                                "column" => $columnIndex,
                            ],
                            "data" => $column->columnName,
                        ];
                    }
                }
            }
        }
        return null;
    }

    public function getTableDetails(string $id): ?array {
        foreach($this->databaseSchemas as $schemaIndex => $schema) {
            foreach($schema->tableDataList as $tableIndex => $table) {
                if($table->id === $id) {
                    return [
                        "index" => [
                            "schema" => $schemaIndex,
                            "table" => $tableIndex,
                        ],
                        "schema" => $schema->schemaName,
                        "table" => $table->tableName,
                    ];
                }
            }
        }
        return null;
    }

    public function getColumnDetails(string $id): ?array {
        foreach($this->databaseSchemas as $schemaIndex => $schema) {
            foreach($schema->tableDataList as $tableIndex => $table) {
                foreach($table->columnDataList as $columnIndex => $column) {
                    if($column->id . "" === $id) {
                        return [
                            "index" => [
                                "schema" => $schemaIndex,
                                "table" => $tableIndex,
                                "column" => $columnIndex,
                            ],
                            "schema" => $schema->schemaName,
                            "table" => $table->tableName,
                            "column" => $column->columnName,
                        ];
                    }
                }
            }
        }
        return null;
    }*/

    private array $schemas;

    public function __construct(array $schemas) {
        $this->schemas = $schemas;
    }

    private function findSchemaIndex(string $id): ?int {
        foreach($this->schemas as $index => $schema) {
            if("{$schema->id}" === "$id") {
                return $index;
            }
        }
        return null;
    }

    private function findTableIndex(string $schemaIndex, string $id): ?int {
        foreach($this->schemas[$schemaIndex]->tableDataList as $index => $table) {
            if("{$table->id}" === "$id") {
                return $index;
            }
        }
        return null;
    }

    private function findColumnIndex(?string $schemaIndex, ?string $tableIndex, ?string $id): ?int {
        foreach($this->schemas[$schemaIndex]->tableDataList[$tableIndex]->columnDataList as $index => $column) {
            if("{$column->id}" === "$id") {
                return $index;
            }
        }
        return null;
    }

    public function getSchemaName(string $id): ?array {
        $schemaIndex = $this->findSchemaIndex($id);
        if($schemaIndex === null) return null;

        return [
            "index" => ["schema" => $schemaIndex],
            "data" => $this->schemas[$schemaIndex]->schemaName,
        ];
    }

    public function getTableName(string $id): ?array {
        foreach($this->schemas as $schemaIndex => $schema) {
            $tableIndex = $this->findTableIndex($schemaIndex, $id);
            if($tableIndex !== null) {
                return [
                    "index" => ["schema" => $schemaIndex, "table" => $tableIndex],
                    "data" => $schema->tableDataList[$tableIndex]->tableName,
                ];
            }
        }
        return null;
    }

    public function getColumnName(string $id): ?array {
        foreach($this->schemas as $schemaIndex => $schema) {
            foreach($schema->tableDataList as $tableIndex => $table) {
                $columnIndex = $this->findColumnIndex($schemaIndex, $tableIndex, $id);
                if($columnIndex !== null) {
                    return [
                        "index" => [
                            "schema" => $schemaIndex,
                            "table" => $tableIndex,
                            "column" => $columnIndex,
                        ],
                        "data" => $table->columnDataList[$columnIndex]->columnName,
                    ];
                }
            }
        }
        return null;
    }

    public function getTableDetails(string $id): ?array {
        foreach($this->schemas as $schemaIndex => $schema) {
            $tableIndex = $this->findTableIndex($schemaIndex, $id);
            if($tableIndex !== null) {
                return [
                    "index" => ["schema" => $schemaIndex, "table" => $tableIndex],
                    "schema" => $schema->schemaName,
                    "table" => $schema->tableDataList[$tableIndex]->tableName,
                ];
            }
        }
        return null;
    }

    public function getColumnDetails(?string $id): ?array {
        foreach($this->schemas as $schemaIndex => $schema) {
            foreach($schema->tableDataList as $tableIndex => $table) {
                $columnIndex = $this->findColumnIndex($schemaIndex, $tableIndex, $id);
                if($columnIndex !== null) {
                    return [
                        "index" => ["schema" => $schemaIndex, "table" => $tableIndex, "column" => $columnIndex],
                        "schema" => $schema->schemaName,
                        "table" => $table->tableName,
                        "column" => $table->columnDataList[$columnIndex]->columnName,
                    ];
                }
            }
        }
        return null;
    }

    public function getMaxColumnLength(string $schemaId): int {
        $maxLength = 0;
        foreach($this->schemas as $schema) {
            if("{$schema->id}" === "$schemaId") {
                foreach($schema->tableDataList as $table) {
                    foreach($table->columnDataList as $column) {
                        $maxLength = max($maxLength, strlen($column->columnName));
                    }
                }
            }
        }
        return $maxLength;
    }

    public function getMaxDataTypeLength(string $schemaId): int {
        $maxLength = 0;
        foreach($this->schemas as $schema) {
            if("{$schema->id}" === "$schemaId") {
                foreach($schema->tableDataList as $table) {
                    foreach($table->columnDataList as $column) {
                        $maxLength = max($maxLength, strlen($column->dataType));
                    }
                }
            }
        }
        return $maxLength;
    }
}
?>