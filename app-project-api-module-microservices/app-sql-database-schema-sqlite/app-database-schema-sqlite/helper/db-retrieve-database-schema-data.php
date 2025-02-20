<?php
namespace App\DatabaseSchema\Helper\Database\Data\Retrieve;
?>
<?php
use App\DatabaseSchema\Data\Entities\DatabaseSchema;
use App\DatabaseSchema\Data\Entities\TableData;
use App\DatabaseSchema\Data\Entities\ColumnData;
use App\DatabaseSchema\Data\Entities\ColumnKey;
use App\DatabaseSchema\Data\Mappers\DatabaseSchemaMapper;
use App\DatabaseSchema\Data\Mappers\TableDataMapper;
use App\DatabaseSchema\Data\Mappers\ColumnDataMapper;
use App\DatabaseSchema\Data\Mappers\ColumnKeyMapper;
use RzSDK\Database\SqliteConnection;
?>
<?php
class DbRetrieveDatabaseSchemaData {
    private SqliteConnection $dbConn;
    //
    public function __construct(SqliteConnection $dbConn = null) {
        if(is_null($dbConn)) {
            $this->dbConn = SqliteConnection::getInstance(DB_FULL_PATH);
        } else {
            $this->dbConn = $dbConn;
        }
    }
    //
    public function getAllDatabaseSchemaData(): array|bool {
        $schemaTableName = "tbl_database_schema";
        $tempDatabaseSchema = new DatabaseSchema();
        $databaseSchemaList = array();
        $sqlQuery = "SELECT * FROM $schemaTableName ORDER BY {$tempDatabaseSchema->schema_name} ASC;";
        //DebugLog::log($sqlQuery);
        $results = $this->dbConn->query($sqlQuery);
        foreach($results as $result) {
            $databaseSchema = DatabaseSchemaMapper::toModel($result);
            $tableDataList = $this->getAllTableDataBySchemaId($databaseSchema->id);
            if($tableDataList) {
                $databaseSchema->tableDataList = $tableDataList;
            }
            if(is_array($tableDataList) && !empty($tableDataList)) {
                $databaseSchemaList[] = $databaseSchema;
            }
        }
        if(!empty($databaseSchemaList)) {
            //DebugLog::log($databaseSchemaList);
            return $databaseSchemaList;
        }
        return false;
    }

    public function getAllTableDataBySchemaId($schemaId): array|bool {
        $tableDataTableName = "tbl_table_data";
        $tempTableData = new TableData();
        $tableDataList = array();
        $sqlQuery = "SELECT * FROM $tableDataTableName WHERE {$tempTableData->schema_id} = '$schemaId' ORDER BY {$tempTableData->table_name} ASC;";
        //DebugLog::log($sqlQuery);
        $results = $this->dbConn->query($sqlQuery);
        foreach($results as $result) {
            $tableData = TableDataMapper::toModel($result);
            $columnDataList = $this->getAllColumnDataByTableId($tableData->id);
            $columnKeyList = $this->getAllColumnKeyByTableId($tableData->id);
            if($columnDataList) {
                $tableData->columnDataList = $columnDataList;
            }
            if($columnKeyList) {
                $tableData->columnKeyList = $columnKeyList;
            }
            if(is_array($columnDataList) && !empty($columnDataList)) {
                $tableDataList[] = $tableData;
            }
        }
        if(!empty($tableDataList)) {
            return $tableDataList;
        }
        return false;
    }

    public function getAllColumnDataByTableId($tableId): array|bool {
        $columnDataTableName = "tbl_column_data";
        $tempColumnData = new ColumnData();
        $columnDataList = array();
        $sqlQuery = "SELECT * FROM $columnDataTableName WHERE {$tempColumnData->table_id} = '$tableId' ORDER BY {$tempColumnData->column_name} ASC;";
        //DebugLog::log($sqlQuery);
        $results = $this->dbConn->query($sqlQuery);
        foreach($results as $result) {
            $columnData = ColumnDataMapper::toModel($result);
            $columnDataList[] = $columnData;
        }
        if(!empty($columnDataList)) {
            return $columnDataList;
        }
        return false;
    }

    public function getAllColumnKeyByTableId($tableId): array|bool {
        $columnKeyTableName = "tbl_column_key";
        $tempColumnKey = new ColumnKey();
        $columnKeyList = array();
        $sqlQuery = "SELECT * FROM $columnKeyTableName WHERE {$tempColumnKey->working_table} = '$tableId' ORDER BY {$tempColumnKey->key_type} ASC, {$tempColumnKey->unique_name};";
        //DebugLog::log($sqlQuery);
        $results = $this->dbConn->query($sqlQuery);
        foreach($results as $result) {
            $columnKey = ColumnKeyMapper::toModel($result);
            $columnKeyList[] = $columnKey;
        }
        if(!empty($columnKeyList)) {
            return $columnKeyList;
        }
        return false;
    }
}
?>