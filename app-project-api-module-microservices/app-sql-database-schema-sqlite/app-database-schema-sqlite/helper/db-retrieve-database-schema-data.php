<?php
namespace App\DatabaseSchema\Helper\Database\Data\Retrieve;
?>
<?php
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
use RzSDK\Database\SqliteConnection;
use RzSDK\Log\DebugLog;
?>
<?php
class DbRetrieveDatabaseSchemaDataNextUpdate {
    private SqliteConnection $dbConn;

    public function __construct(SqliteConnection $dbConn = null) {
        $this->dbConn = $dbConn ?? SqliteConnection::getInstance(DB_FULL_PATH);
    }

    public function getAllDatabaseSchemaData(?string $schemaId = "", ?string $tableId = "", ?string $notTableId = ""): array {
        $schemaTableName = "tbl_database_schema";
        $tempDatabaseSchema = new DatabaseSchema();
        $databaseSchemaList = [];

        $sqlQuery = "SELECT * FROM $schemaTableName ORDER BY {$tempDatabaseSchema->schema_name} ASC;";
        if (!empty($schemaId)) {
            //$safeSchemaId = $this->dbConn->quote($schemaId);
            $sqlQuery = "SELECT * FROM $schemaTableName WHERE {$tempDatabaseSchema->id} = $schemaId ORDER BY {$tempDatabaseSchema->schema_name} ASC;";
        }

        $results = $this->dbConn->query($sqlQuery);
        foreach ($results as $result) {
            $databaseSchema = DatabaseSchemaMapper::toModel($result);
            $tableDataList = $this->getAllTableDataBySchemaId($databaseSchema->id, $tableId, $notTableId);
            if (!empty($tableDataList)) {
                $databaseSchema->tableDataList = $tableDataList;
            }
            $databaseSchemaList[] = $databaseSchema;
        }

        return $databaseSchemaList;
    }

    public function getAllTableDataBySchemaId(?string $schemaId = "", ?string $tableId = "", ?string $notTableId = ""): array {
        $tableDataTableName = "tbl_table_data";
        $tempTableData = new TableData();
        $tableDataList = [];

        $whereClauses = [];
        if (!empty($schemaId)) {
            $whereClauses[] = "{$tempTableData->schema_id} = " . $this->dbConn->quote($schemaId);
        }
        if (!empty($tableId)) {
            $whereClauses[] = "{$tempTableData->id} = " . $this->dbConn->quote($tableId);
        }
        if (!empty($notTableId)) {
            $whereClauses[] = "{$tempTableData->id} <> " . $this->dbConn->quote($notTableId);
        }

        $whereSql = $whereClauses ? "WHERE " . implode(" AND ", $whereClauses) : "";
        $sqlQuery = "SELECT * FROM $tableDataTableName $whereSql ORDER BY {$tempTableData->table_order} ASC, {$tempTableData->table_name} ASC;";

        $results = $this->dbConn->query($sqlQuery);
        foreach ($results as $result) {
            $tableData = TableDataMapper::toModel($result);
            $tableData->columnDataList = $this->getAllColumnDataByTableId($tableData->id);
            $tableData->columnKeyList = $this->getAllColumnKeyByTableId($tableData->id);
            $tableDataList[] = $tableData;
        }

        return $tableDataList;
    }

    public function getAllColumnDataByTableId(?string $tableId = "", ?string $columnId = ""): array {
        $columnDataTableName = "tbl_column_data";
        $tempColumnData = new ColumnData();
        $columnDataList = [];

        $whereClauses = [];
        if (!empty($tableId)) {
            $whereClauses[] = "{$tempColumnData->table_id} = " . $this->dbConn->quote($tableId);
        }
        if (!empty($columnId)) {
            $whereClauses[] = "{$tempColumnData->id} = " . $this->dbConn->quote($columnId);
        }

        $whereSql = $whereClauses ? "WHERE " . implode(" AND ", $whereClauses) : "";
        $sqlQuery = "SELECT * FROM $columnDataTableName $whereSql ORDER BY {$tempColumnData->column_order} ASC, {$tempColumnData->column_name} ASC;";

        $results = $this->dbConn->query($sqlQuery);
        foreach ($results as $result) {
            $columnDataList[] = ColumnDataMapper::toModel($result);
        }

        return $columnDataList;
    }

    public function getAllColumnKeyByTableId(string $tableId): array {
        $columnKeyTableName = "tbl_column_key";
        $tempColumnKey = new ColumnKey();
        $columnKeyList = [];

        $sqlQuery = "SELECT * FROM $columnKeyTableName WHERE {$tempColumnKey->working_table} = " . $this->dbConn->quote($tableId) . " ORDER BY {$tempColumnKey->key_type} ASC, {$tempColumnKey->unique_name};";

        $results = $this->dbConn->query($sqlQuery);
        foreach ($results as $result) {
            $columnKey = ColumnKeyMapper::toModel($result);
            $columnKey->compositeKeyList = $this->getAllCompositeKeyByColumnKeyId($columnKey->id);
            $columnKeyList[] = $columnKey;
        }

        return $columnKeyList;
    }

    public function getAllCompositeKeyByColumnKeyId(string $columnKeyId): array {
        $compositeKeyTableName = "tbl_composite_key";
        $tempCompositeKey = new CompositeKey();
        $compositeKeyList = [];

        $sqlQuery = "SELECT * FROM $compositeKeyTableName WHERE {$tempCompositeKey->key_id} = " . $this->dbConn->quote($columnKeyId) . ";";

        $results = $this->dbConn->query($sqlQuery);
        foreach ($results as $result) {
            $compositeKeyList[] = CompositeKeyMapper::toModel($result);
        }

        return $compositeKeyList;
    }

    public function getAllDatabaseSchemaDataOnly(?string $schemaId = ""): array {
        $schemaTableName = "tbl_database_schema";
        $tempDatabaseSchema = new DatabaseSchema();
        $databaseSchemaList = [];

        $sqlQuery = "SELECT * FROM $schemaTableName ORDER BY {$tempDatabaseSchema->schema_name} ASC;";
        if (!empty($schemaId)) {
            $safeSchemaId = $this->dbConn->quote($schemaId);
            $sqlQuery = "SELECT * FROM $schemaTableName WHERE {$tempDatabaseSchema->id} = $safeSchemaId ORDER BY {$tempDatabaseSchema->schema_name} ASC;";
        }

        $results = $this->dbConn->query($sqlQuery);
        foreach ($results as $result) {
            $databaseSchemaList[] = DatabaseSchemaMapper::toModel($result);
        }

        return $databaseSchemaList;
    }
}
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
    public function getAllDatabaseSchemaData(?string $schemaId = "", ?string $tableId = "", ?string $notTableId = ""): array|bool {
        $schemaTableName = "tbl_database_schema";
        $tempDatabaseSchema = new DatabaseSchema();
        $databaseSchemaList = array();
        $sqlQuery = "SELECT * FROM $schemaTableName ORDER BY {$tempDatabaseSchema->schema_name} ASC;";
        if(!empty($schemaId)) {
            $sqlQuery = "SELECT * FROM $schemaTableName WHERE {$tempDatabaseSchema->id} = $schemaId ORDER BY {$tempDatabaseSchema->schema_name} ASC;";
        }
        //DebugLog::log($sqlQuery);
        $results = $this->dbConn->query($sqlQuery);
        foreach($results as $result) {
            $databaseSchema = DatabaseSchemaMapper::toModel($result);
            /*if(!empty($tableId)) {
                $tableDataList = $this->getAllTableDataBySchemaId($databaseSchema->id, $tableId, $notTableId);
            } else {
                $tableDataList = $this->getAllTableDataBySchemaId($databaseSchema->id, $tableId, $notTableId);
            }*/
            $tableDataList = $this->getAllTableDataBySchemaId($databaseSchema->id, $tableId, $notTableId);
            if($tableDataList) {
                $databaseSchema->tableDataList = $tableDataList;
            }
            /*if(is_array($tableDataList) && !empty($tableDataList)) {
                $databaseSchemaList[] = $databaseSchema;
            }*/
            $databaseSchemaList[] = $databaseSchema;
        }
        if(!empty($databaseSchemaList)) {
            //DebugLog::log($databaseSchemaList);
            return $databaseSchemaList;
        }
        return false;
    }

    public function getAllTableDataBySchemaId($schemaId = "", $tableId = "", $notTableId = ""): array|bool {
        $tableDataTableName = "tbl_table_data";
        $tempTableData = new TableData();
        $tableDataList = array();
        $sqlQuery = "SELECT * FROM $tableDataTableName ORDER BY {$tempTableData->table_order} ASC, {$tempTableData->table_name} ASC;";
        if(!empty($notTableId)) {
            $sqlQuery = "SELECT * FROM $tableDataTableName WHERE {$tempTableData->id} <> '$notTableId' ORDER BY {$tempTableData->table_order} ASC, {$tempTableData->table_name} ASC;";
        }
        //DebugLog::log($sqlQuery);
        if(!empty($schemaId) && !empty($tableId)) {
            $sqlQuery = "SELECT * FROM $tableDataTableName WHERE {$tempTableData->schema_id} = '$schemaId' AND {$tempTableData->id} = '$tableId' ORDER BY {$tempTableData->table_order} ASC, {$tempTableData->table_name} ASC;";
            if(!empty($notTableId)) {
                $sqlQuery = "SELECT * FROM $tableDataTableName WHERE {$tempTableData->schema_id} = '$schemaId' AND {$tempTableData->id} = '$tableId' AND {$tempTableData->id} <> '$notTableId' ORDER BY {$tempTableData->table_order} ASC, {$tempTableData->table_name} ASC;";
            }
        }  else if(!empty($tableId)) {
            $sqlQuery = "SELECT * FROM $tableDataTableName WHERE {$tempTableData->id} = '$tableId' ORDER BY {$tempTableData->table_order} ASC, {$tempTableData->table_name} ASC;";
            if(!empty($notTableId)) {
                $sqlQuery = "SELECT * FROM $tableDataTableName WHERE {$tempTableData->id} = '$tableId' AND {$tempTableData->id} <> '$notTableId' ORDER BY {$tempTableData->table_order} ASC, {$tempTableData->table_name} ASC;";
            }
        } else if(!empty($schemaId)) {
            $sqlQuery = "SELECT * FROM $tableDataTableName WHERE {$tempTableData->schema_id} = '$schemaId' ORDER BY {$tempTableData->table_order} ASC, {$tempTableData->table_name} ASC;";
            if(!empty($notTableId)) {
                $sqlQuery = "SELECT * FROM $tableDataTableName WHERE {$tempTableData->schema_id} = '$schemaId' AND {$tempTableData->id} <> '$notTableId' ORDER BY {$tempTableData->table_order} ASC, {$tempTableData->table_name} ASC;";
            }
        }
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
            /*if(is_array($columnDataList) && !empty($columnDataList)) {
                $tableDataList[] = $tableData;
            }*/
            $tableDataList[] = $tableData;
        }
        if(!empty($tableDataList)) {
            return $tableDataList;
        }
        return false;
    }

    public function getAllColumnDataByTableId($tableId = "", $columnId = ""): array|bool {
        $columnDataTableName = "tbl_column_data";
        $tempColumnData = new ColumnData();
        $columnDataList = array();
        $sqlQuery = "SELECT * FROM $columnDataTableName ORDER BY {$tempColumnData->column_order} ASC, {$tempColumnData->column_name} ASC;";
        if(!empty($tableId) && !empty($columnId)) {
            $sqlQuery = "SELECT * FROM $columnDataTableName WHERE {$tempColumnData->table_id} = '$tableId' AND {$tempColumnData->id} = '$columnId' ORDER BY {$tempColumnData->column_order} ASC, {$tempColumnData->column_name} ASC;";
        } else if(!empty($tableId)) {
            $sqlQuery = "SELECT * FROM $columnDataTableName WHERE {$tempColumnData->table_id} = '$tableId' ORDER BY {$tempColumnData->column_order} ASC, {$tempColumnData->column_name} ASC;";
        } else if(!empty($columnId)) {
            $sqlQuery = "SELECT * FROM $columnDataTableName WHERE {$tempColumnData->id} = '$columnId' ORDER BY {$tempColumnData->column_order} ASC, {$tempColumnData->column_name} ASC;";
        }
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
        //$sqlQuery = "SELECT * FROM $columnKeyTableName WHERE {$tempColumnKey->working_table} = '$tableId' GROUP BY {$tempColumnKey->key_type} ORDER BY {$tempColumnKey->key_type} ASC, {$tempColumnKey->unique_name};";
        $sqlQuery = "SELECT * FROM $columnKeyTableName WHERE {$tempColumnKey->working_table} = '$tableId' ORDER BY {$tempColumnKey->key_type} ASC, {$tempColumnKey->unique_name};";
        //DebugLog::log($sqlQuery);
        $results = $this->dbConn->query($sqlQuery);
        foreach($results as $result) {
            $columnKey = ColumnKeyMapper::toModel($result);
            $compositeKeyList = $this->getAllCompositeKeyByColumnKeyId($columnKey->id);
            if($compositeKeyList) {
                $columnKey->compositeKeyList = $compositeKeyList;
            }
            $columnKeyList[] = $columnKey;
        }
        if(!empty($columnKeyList)) {
            return $columnKeyList;
        }
        return false;
    }

    public function getAllCompositeKeyByColumnKeyId($columnKeyId): array|bool {
        $compositeKeyTableName = "tbl_composite_key";
        $tempCompositeKey = new CompositeKey();
        $compositeKeyList = array();
        $sqlQuery = "SELECT * FROM $compositeKeyTableName WHERE {$tempCompositeKey->key_id} = '$columnKeyId';";
        //DebugLog::log($sqlQuery);
        $results = $this->dbConn->query($sqlQuery);
        foreach($results as $result) {
            $compositeKey = CompositeKeyMapper::toModel($result);
            $compositeKeyList[] = $compositeKey;
        }
        if(!empty($compositeKeyList)) {
            return $compositeKeyList;
        }
        return false;
    }

    public function getAllDatabaseSchemaDataOnly(?string $schemaId = ""): array|bool {
        $schemaTableName = "tbl_database_schema";
        $tempDatabaseSchema = new DatabaseSchema();
        $databaseSchemaList = array();
        $sqlQuery = "SELECT * FROM $schemaTableName ORDER BY {$tempDatabaseSchema->schema_name} ASC;";
        if(!empty($schemaId)) {
            $sqlQuery = "SELECT * FROM $schemaTableName WHERE {$tempDatabaseSchema->id} = $schemaId ORDER BY {$tempDatabaseSchema->schema_name} ASC;";
        }
        //DebugLog::log($sqlQuery);
        $results = $this->dbConn->query($sqlQuery);
        foreach($results as $result) {
            $databaseSchema = DatabaseSchemaMapper::toModel($result);
            $databaseSchemaList[] = $databaseSchema;
        }
        if(!empty($databaseSchemaList)) {
            //DebugLog::log($databaseSchemaList);
            return $databaseSchemaList;
        }
        return false;
    }
}
?>