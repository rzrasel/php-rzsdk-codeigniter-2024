<?php
namespace App\DatabaseSchema\Schema\Build\Entity;
?>
<?php
use RzSDK\Log\DebugLog;
?>
<?php
class ExtractSqlStatementToDataEntity {
    private $sqlToDataEntityList = array();

    public function toDataEntity(string $sqlStatement): array {
        $tableName = $this->getTable($sqlStatement);
        $columnList = $this->getColumns($sqlStatement);
        $constraintList = $this->getConstraints($tableName, $sqlStatement);
        $this->sqlToDataEntityList["table"] = $tableName;
        $this->sqlToDataEntityList["column"] = $columnList;
        $this->sqlToDataEntityList["constraint"] = $constraintList;
        return $this->sqlToDataEntityList;
    }

    private function getTable(string $sqlStatement): ?string {
        // Extract table name
        preg_match("/CREATE TABLE IF NOT EXISTS\s+(\w+)\s*\(/", $sqlStatement, $tableMatches);
        if (!empty($tableMatches[1])) {
            return $tableMatches[1];
        }
        return null;
    }

    private function getColumns(string $sqlStatement): array {
        $columnDataList = array();

        // Extract everything inside parentheses
        if (preg_match('/\((.*)\);/s', $sqlStatement, $tableMatch)) {
            $columnDefinitions = trim($tableMatch[1]);

            // Split column definitions by commas, but handle nested parentheses
            $columns = $this->splitColumnDefinitions($columnDefinitions);

            foreach ($columns as $index => $columnDefinition) {
                $columnData = $this->parseColumnDefinition($columnDefinition, $index + 1);
                if ($columnData) {
                    $columnDataList[] = $columnData;
                }
            }
        }
        return $columnDataList;
    }

    private function splitColumnDefinitions(string $columnDefinitions): array {
        $columns = array();
        $buffer = '';
        $depth = 0;

        // Split by commas, but respect nested parentheses
        foreach (explode(',', $columnDefinitions) as $part) {
            $buffer .= $part;
            $depth += substr_count($part, '(') - substr_count($part, ')');

            if ($depth === 0) {
                $columns[] = trim($buffer);
                $buffer = '';
            } else {
                $buffer .= ',';
            }
        }

        return $columns;
    }

    private function parseColumnDefinition(string $columnDefinition, int $columnOrder): array {
        // Skip constraint definitions
        if (preg_match('/^CONSTRAINT/', $columnDefinition)) {
            return [];
        }

        // Extract column name, data type, and constraints
        if (preg_match('/^([\w`]+)\s+([\w\(\)]+)\s*(.*)/', $columnDefinition, $matches)) {
            $columnData = array();
            $columnData["column_name"] = trim($matches[1], '`'); // Remove backticks if present
            $columnData["data_type"] = $matches[2];
            $columnData["column_order"] = $columnOrder;

            // Extract constraints
            $constraints = trim($matches[3] ?? '');
            $columnData["constraints"] = $constraints;

            // Handle NULLABLE
            $columnData["is_nullable"] = (stripos($constraints, 'NOT NULL') === false) ? "true" : "false";

            // Handle DEFAULT value
            $columnData["have_default"] = (stripos($constraints, 'DEFAULT') !== false) ? "true" : "false";
            if ($columnData["have_default"] === "true") {
                preg_match('/DEFAULT\s+(\'[^\']*\'|[\w\(\)]+)/i', $constraints, $defaultMatches);
                $columnData["default_value"] = trim($defaultMatches[1] ?? null);
            } else {
                preg_match('/(NULL|NOT NULL|DEFAULT)\s+((?:\'[^\']*\'|\w+))/i', $constraints, $defaultMatches);
                if(!empty($defaultMatches)) {
                    $columnData["default_value"] = $defaultMatches[2] ?? null;
                } else {
                    $columnData["default_value"] = null;
                }
            }

            $defaultValue = str_ireplace(array("NOT NULL", "DEFAULT", "NULL"), "", $constraints);
            $columnData["default_value"] = !empty(trim($defaultValue)) ? trim($defaultValue) : null;

            // Handle CHECK constraints
            if (preg_match('/CHECK\s*\((.*?)\)/i', $constraints, $checkMatches)) {
                $columnData["check_constraint"] = trim($checkMatches[1]);
            } else {
                $columnData["check_constraint"] = null;
            }

            return $columnData;
        }

        return [];
    }

    private function getConstraints(string $tableName, string $sqlStatement): array {
        $constraintDataList = array();
        preg_match('/\((.*)\);/s', $sqlStatement, $columnMatches);
        if (!empty($columnMatches[1])) {
            $columnDefinitions = explode(",", trim($columnMatches[1]));

            foreach ($columnDefinitions as $columnDefinition) {
                $columnDefinition = trim($columnDefinition);

                if (preg_match('/CONSTRAINT\s+(\w+)\s+PRIMARY KEY\s*\(([^)]+)\)/', $columnDefinition, $pkMatches)) {
                    $constraintDataList[] = [
                        "key_name" => $pkMatches[1],
                        "key_type" => "primary key",
                        "working_table" => $tableName,
                        "main_column" => trim($pkMatches[2]),
                        "reference_column" => []
                    ];
                }

                if (preg_match('/CONSTRAINT\s+(\w+)\s+FOREIGN KEY\s*\(([^)]+)\)\s+REFERENCES\s+(\w+)\s*\(([^)]+)\)/', $columnDefinition, $fkMatches)) {
                    $constraintDataList[] = [
                        "key_name" => $fkMatches[1],
                        "key_type" => "foreign key",
                        "working_table" => $tableName,
                        "main_column" => trim($fkMatches[2]),
                        "reference_column" => [trim($fkMatches[3]), trim($fkMatches[4])]
                    ];
                }

                if (preg_match('/CONSTRAINT\s+(\w+)\s+UNIQUE\s*\(([^)]+)\)/', $columnDefinition, $ukMatches)) {
                    $constraintDataList[] = [
                        "key_name" => $ukMatches[1],
                        "key_type" => "unique key",
                        "working_table" => $tableName,
                        "main_column" => trim($ukMatches[2]),
                        "reference_column" => []
                    ];
                }
            }
        }
        return $constraintDataList;
    }

    private function getConstraintsV1(string $tableName, string $sqlStatement): array {
        $constraintDataList = array();

        // Extract everything inside parentheses
        if (preg_match('/\((.*)\);/s', $sqlStatement, $tableMatch)) {
            $columnDefinitions = trim($tableMatch[1]);

            // Split column definitions by commas, but handle nested parentheses
            $columns = $this->splitColumnDefinitions($columnDefinitions);

            foreach ($columns as $columnDefinition) {
                $columnDefinition = trim($columnDefinition);

                // Handle PRIMARY KEY constraints
                if (preg_match('/CONSTRAINT\s+(\w+)\s+PRIMARY KEY\s*\(([^)]+)\)/', $columnDefinition, $pkMatches)) {
                    $constraintData = array();
                    $constraintData["key_name"] = $pkMatches[1];
                    $constraintData["key_type"] = "primary key";
                    $constraintData["working_table"] = $tableName;
                    $constraintData["main_column"] = trim($pkMatches[2]);
                    $constraintData["reference_column"] = []; // PRIMARY KEY has no reference column
                    $constraintDataList[] = $constraintData;
                }

                // Handle FOREIGN KEY constraints
                if (preg_match('/CONSTRAINT\s+(\w+)\s+FOREIGN KEY\s*\(([^)]+)\)\s+REFERENCES\s+(\w+)\s*\(([^)]+)\)/', $columnDefinition, $fkMatches)) {
                    $constraintData = array();
                    $constraintData["key_name"] = $fkMatches[1];
                    $constraintData["key_type"] = "foreign key";
                    $constraintData["working_table"] = $tableName;
                    $constraintData["main_column"] = trim($fkMatches[2]);
                    $constraintData["reference_column"] = [trim($fkMatches[3]), trim($fkMatches[4])]; // [referenced_table, referenced_column]
                    $constraintDataList[] = $constraintData;
                }
            }
        }

        return $constraintDataList;
    }
}
?>