<?php
namespace App\DatabaseSchema\Helper\Database\Data\Retrieve;
?>
<?php
use RzSDK\Database\SqliteConnection;
use App\DatabaseSchema\Data\Entities\DatabaseSchema;
use App\DatabaseSchema\Data\Entities\TableData;
use App\DatabaseSchema\Data\Entities\ColumnData;
use App\DatabaseSchema\Data\Entities\ColumnKey;
use App\DatabaseSchema\Data\Entities\CompositeKey;
use App\DatabaseSchema\Data\Mappers\DatabaseSchemaMapper;
use App\DatabaseSchema\Data\Mappers\TableDataMapper;
use App\DatabaseSchema\Data\Mappers\ColumnDataMapper;
use App\DatabaseSchema\Data\Mappers\ColumnKeyMapper;
use App\DatabaseSchema\Data\Mappers\CompositeKeyMapper;
use RzSDK\Log\DebugLog;
?>
<?php
class DatabaseSchemaRawQuery {
    private SqliteConnection $dbConn;

    public function __construct(SqliteConnection $dbConn = null) {
        $this->dbConn = $dbConn ?? SqliteConnection::getInstance(DB_FULL_PATH);
    }

    public function getDatabaseSchema($schemaId = "", $tableId = "", $notTableId = "", callable $callback = null, $depth = 0): array {
        $data = "";
        $databaseSchemaList = [
            "schema" => [],
            "data" => "",
        ];
        $schemaTableName = "tbl_database_schema";
        $tempDatabaseSchema = new DatabaseSchema();
        $sqlString = "SELECT * FROM {$schemaTableName}%s ORDER BY {$tempDatabaseSchema->schema_name} ASC;";

        $sqlQuery = !empty($schemaId)
            ? sprintf($sqlString, " WHERE {$tempDatabaseSchema->id} = '{$schemaId}'")
            : sprintf($sqlString, "");

        $results = $this->dbConn->query($sqlQuery);
        foreach($results as $result) {
            $databaseSchema = DatabaseSchemaMapper::toModel($result);
            //$tableDataList = $this->getTableBySchemaId($databaseSchema->id, $tableId, $notTableId, $callback, $depth + 1);
            $tableDataList = $this->getTableBySchemaId($databaseSchema->id, $tableId, $notTableId, $callback, $depth + 1);
            //$tableDataList = [];

            if(!empty($tableDataList["table"])) {
                if(!empty($callback)) {
                    $data .= $callback($databaseSchema, $depth, true); // Open parent
                    $databaseSchema->tableDataList = $tableDataList["table"];
                    $data .= $tableDataList["data"];
                    $data .= $callback($databaseSchema, $depth, false, true); // Close parent
                } else {
                    $databaseSchema->tableDataList = $tableDataList["table"];
                }
            } else {
                if(!empty($callback)) {
                    $data .= $callback($databaseSchema, $depth, false);
                }
            }

            $databaseSchemaList["schema"][] = $databaseSchema;
            $databaseSchemaList["data"] = $data;
        }

        return !empty($databaseSchemaList["schema"]) ? $databaseSchemaList : [];
    }

    public function getTableBySchemaId($schemaId = "", $tableId = "", $notTableId = "", callable $callback = null, $depth = 0): array {
        $data = "";
        $databaseTableList = [
            "table" => [],
            "data" => "",
        ];
        $tableDataTableName = "tbl_table_data";
        $tempTableData = new TableData();
        $whereClauses = [];

        if(!empty($schemaId)) {
            $whereClauses[] = "{$tempTableData->schema_id} = '{$schemaId}'";
        }

        if(!empty($tableId)) {
            $whereClauses[] = "{$tempTableData->id} = '{$tableId}'";
        }

        if(!empty($notTableId)) {
            $whereClauses[] = "{$tempTableData->id} <> '{$notTableId}'";
        }

        $sqlString = "SELECT * FROM {$tableDataTableName}%s ORDER BY {$tempTableData->table_order} ASC, {$tempTableData->table_name} ASC;";
        //
        $sqlQuery = !empty($whereClauses)
            ? sprintf($sqlString, " WHERE " . implode(" AND ", $whereClauses))
            : sprintf($sqlString, "");
        //DebugLog::log($sqlQuery);

        $results = $this->dbConn->query($sqlQuery);
        foreach($results as $result) {
            $tableData = TableDataMapper::toModel($result);
            $columnDataList = $this->getColumnByTableId($tableData->id, "", $callback, $depth + 1);
            $columnKeyList = $this->getColumnKeyByTableId($tableData->id, $callback, $depth + 1);

            if(!empty($columnDataList["column"])) {
                if(!empty($callback)) {
                    $data .= $callback($tableData, $depth, true); // Open parent
                    $tableData->columnDataList = $columnDataList["column"];
                    $data .= $columnDataList["data"];
                    $data .= $callback($tableData, $depth, false, true); // Close parent
                } else {
                    $tableData->columnDataList = $columnDataList["column"];
                }
            } else {
                if(!empty($callback)) {
                    $data .= $callback($tableData, $depth, false);
                }
            }

            if(!empty($columnKeyList["column"])) {
                if(!empty($callback)) {
                    $data .= $callback($tableData, $depth, true); // Open parent
                    $tableData->columnKeyList = $columnKeyList["column"];
                    $data .= $columnKeyList["data"];
                    $data .= $callback($tableData, $depth, false, true); // Close parent
                } else {
                    $tableData->columnKeyList = $columnKeyList["column"];
                }
            } else {
                if(!empty($callback)) {
                    $data .= $callback($tableData, $depth, false);
                }
            }

            /*if(!empty($callback)) {
                $data .= $callback($tableData, $depth, false); // Child items
            }*/

            $databaseTableList["table"][] = $tableData;
            $databaseTableList["data"] = $data;
        }

        return !empty($databaseTableList["table"]) ? $databaseTableList : [];
    }

    public function getColumnByTableId($tableId = "", $columnId = "", callable $callback = null, $depth = 0): array {
        $data = "";
        $databaseColumnList = [
            "column" => [],
            "data" => "",
        ];
        $columnDataTableName = "tbl_column_data";
        $tempColumnData = new ColumnData();
        $whereClauses = [];
        if(!empty($tableId)) {
            $whereClauses[] = "{$tempColumnData->table_id} = '{$tableId}'";
        }
        if(!empty($columnId)) {
            $whereClauses[] = "{$tempColumnData->id} = {$columnId}";
        }
        //
        $sqlString = "SELECT * FROM {$columnDataTableName}%s ORDER BY {$tempColumnData->column_order} ASC, {$tempColumnData->column_name} ASC;";
        //
        $sqlQuery = !empty($whereClauses)
            ? sprintf($sqlString, " WHERE " . implode(" AND ", $whereClauses))
            : sprintf($sqlString, "");
        //DebugLog::log($sqlQuery);
        //
        $results = $this->dbConn->query($sqlQuery);
        foreach($results as $result) {
            $columnData = ColumnDataMapper::toModel($result);
            if(!empty($callback)) {
                $data .= $callback($columnData, $depth, false); // Child items
            }
            $databaseColumnList["column"][] = $columnData;
            $databaseColumnList["data"] = $data;
        }
        return !empty($databaseColumnList["column"]) ? $databaseColumnList : [];
    }

    public function getColumnKeyByTableId($tableId = "", callable $callback = null, $depth = 0): array {
        $data = "";
        $databaseColumnKeyList = [
            "column" => [],
            "data" => "",
        ];
        $columnKeyTableName = "tbl_column_key";
        $tempColumnKey = new ColumnKey();
        //
        if(!empty($tableId)) {
            $whereClauses[] = "{$tempColumnKey->working_table} = '{$tableId}'";
        }
        //
        $sqlString = "SELECT * FROM {$columnKeyTableName}%s ORDER BY {$tempColumnKey->key_type} ASC, {$tempColumnKey->unique_name};";
        //
        $sqlQuery = !empty($whereClauses)
            ? sprintf($sqlString, " WHERE " . implode(" AND ", $whereClauses))
            : sprintf($sqlString, "");
        //DebugLog::log($sqlQuery);
        //
        $results = $this->dbConn->query($sqlQuery);
        foreach($results as $result) {
            $columnKey = ColumnKeyMapper::toModel($result);
            $compositeKeyList = $this->getCompositeKeyByColumnKeyId($columnKey->id, $callback, $depth + 1);
            //
            if(!empty($compositeKeyList["column"])) {
                if(!empty($callback)) {
                    $data .= $callback($columnKey, $depth, true); // Open parent
                    $columnKey->compositeKeyList = $compositeKeyList["column"];
                    $data .= $compositeKeyList["data"];
                    $data .= $callback($columnKey, $depth, false, true); // Close parent
                } else {
                    $columnKey->compositeKeyList = $compositeKeyList["column"];
                }
            } else {
                if(!empty($callback)) {
                    $data .= $callback($columnKey, $depth, false);
                }
            }
            //
            /*if(!empty($callback)) {
                $data .= $callback($columnKey, $depth, false); // Child items
            }*/

            $databaseColumnKeyList["column"][] = $columnKey;
            $databaseColumnKeyList["data"] = $data;
        }
        //DebugLog::log($databaseColumnKeyList);
        //
        return !empty($databaseColumnKeyList["column"]) ? $databaseColumnKeyList : [];
    }

    public function getCompositeKeyByColumnKeyId($columnKeyId, callable $callback = null, $depth = 0): array {
        $data = "";
        $databaseCompositeKeyList = [
            "column" => [],
            "data" => "",
        ];
        $compositeKeyTableName = "tbl_composite_key";
        $tempCompositeKey = new CompositeKey();
        //
        $sqlQuery = "SELECT * FROM $compositeKeyTableName WHERE {$tempCompositeKey->key_id} = '{$columnKeyId}';";
        $results = $this->dbConn->query($sqlQuery);
        foreach($results as $result) {
            $compositeKey = CompositeKeyMapper::toModel($result);
            if(!empty($callback)) {
                $data .= $callback($compositeKey, $depth, false); // Child items
            }

            $databaseCompositeKeyList["column"][] = $compositeKey;
            $databaseCompositeKeyList["data"] = $data;
        }
        return !empty($databaseCompositeKeyList["column"]) ? $databaseCompositeKeyList : [];
    }
}
?>