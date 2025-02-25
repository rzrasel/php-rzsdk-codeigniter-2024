<?php
namespace App\DatabaseSchema\Helper\Database\Data\Retrieve;
?>
<?php
use RzSDK\Database\SqliteConnection;
use App\DatabaseSchema\Data\Entities\DatabaseSchema;
use App\DatabaseSchema\Data\Entities\TableData;
use App\DatabaseSchema\Data\Mappers\DatabaseSchemaMapper;
use App\DatabaseSchema\Data\Mappers\TableDataMapper;
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
            ? sprintf($sqlString, " WHERE {$tempDatabaseSchema->id} = '$schemaId'")
            : sprintf($sqlString, "");

        $results = $this->dbConn->query($sqlQuery);
        foreach ($results as $result) {
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
                //echo "else ";
                if(!empty($callback)) {
                    //echo "else callback ";
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
        $whereClause = [];

        $sqlString = "SELECT * FROM {$tableDataTableName}%s ORDER BY {$tempTableData->table_order} ASC, {$tempTableData->table_name} ASC;";

        if (!empty($schemaId)) {
            $whereClause[] = "{$tempTableData->schema_id} = '$schemaId'";
        }

        if (!empty($tableId)) {
            $whereClause[] = "{$tempTableData->id} = '$tableId'";
        }

        if (!empty($notTableId)) {
            $whereClause[] = "{$tempTableData->id} <> '$notTableId'";
        }

        $sqlQuery = !empty($whereClause)
            ? sprintf($sqlString, " WHERE " . implode(" AND ", $whereClause))
            : sprintf($sqlString, "");

        $results = $this->dbConn->query($sqlQuery);
        foreach ($results as $result) {
            $tableData = TableDataMapper::toModel($result);

            if (!empty($callback)) {
                $data .= $callback($tableData, $depth, false); // Child items
            }

            $databaseTableList["table"][] = $tableData;
            $databaseTableList["data"] = $data;
        }

        return !empty($databaseTableList["table"]) ? $databaseTableList : [];
    }

    public function getColumnDataByTableId(?string $tableId = "", ?string $columnId = "", callable $callback = null, $depth = 0): array {
        $data = "";
        $databaseColumnList = [
            "column" => [],
            "data" => "",
        ];
        $columnDataTableName = "tbl_column_data";
        return !empty($databaseColumnList["column"]) ? $databaseColumnList : [];
    }

    public function getColumnKeyByTableId(?string $tableId = "", callable $callback = null, $depth = 0): array {
        $data = "";
        $databaseColumnKeyList = [
            "column" => [],
            "data" => "",
        ];
        $columnKeyTableName = "tbl_column_key";
        return !empty($databaseColumnList["column"]) ? $databaseColumnList : [];
    }

    public function getCompositeKeyByColumnKeyId(string $columnKeyId, callable $callback = null, $depth = 0): array {
        $data = "";
        $databaseColumnKeyList = [
            "column" => [],
            "data" => "",
        ];
        $compositeKeyTableName = "tbl_composite_key";
        return !empty($databaseColumnList["column"]) ? $databaseColumnList : [];
    }
}
?>