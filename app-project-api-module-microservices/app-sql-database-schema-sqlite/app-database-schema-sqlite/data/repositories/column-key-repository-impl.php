<?php
namespace App\DatabaseSchema\Data\Repositories;
?>
<?php

use App\DatabaseSchema\Data\Mappers\ColumnDataMapper;
use RzSDK\Database\SqliteConnection;
use App\DatabaseSchema\Domain\Repositories\ColumnKeyRepositoryInterface;
use App\DatabaseSchema\Data\Entities\DatabaseSchema;
use App\DatabaseSchema\Data\Entities\TableData;
use App\DatabaseSchema\Data\Entities\ColumnData;
use App\DatabaseSchema\Data\Entities\ColumnKey;
use App\DatabaseSchema\Domain\Models\ColumnKeyModel;
use App\DatabaseSchema\Data\Mappers\DatabaseSchemaMapper;
use App\DatabaseSchema\Data\Mappers\TableDataMapper;
use App\DatabaseSchema\Data\Mappers\ColumnKeyMapper;
use RzSDK\Log\DebugLog;
use RzSDK\Log\LogType;
?>
<?php
class ColumnKeyRepositoryImpl implements ColumnKeyRepositoryInterface {
    private SqliteConnection $dbConn;

    public function __construct(SqliteConnection $dbConn = null) {
        if(is_null($dbConn)) {
            $this->dbConn = SqliteConnection::getInstance(DB_FULL_PATH);
        } else {
            $this->dbConn = $dbConn;
        }
    }

    public function getAllTableDataGroupByTable(): array|bool {
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
            $columnDataList = $this->getAllColumnDataBySchemaId($tableData->id);
            if($columnDataList) {
                $tableData->columnDataList = $columnDataList;
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

    public function getAllColumnDataBySchemaId($tableId): array|bool {
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

    public function create(ColumnKeyModel $columnKey): void {
        $columnKeyTableName = "tbl_column_key";
        //DebugLog::log($tableData);
        $params = ColumnKeyMapper::toDomainParams($columnKey);
        $tempTableData = new ColumnKey();
        $dataVarList = $tempTableData->getVarList();
        $columns = "";
        $values = "";
        foreach($dataVarList as $var) {
            $columns .= "$var, ";
            $values .= ":$var, ";
        }
        $columns = trim(trim($columns), ",");
        $values = trim(trim($values), ",");
        $sqlQuery = "INSERT INTO $columnKeyTableName ($columns) VALUES ($values)";
        //DebugLog::log($sqlQuery);
        //DebugLog::log($values);
        //DebugLog::log($params);
        $this->dbConn->execute($sqlQuery, $params);
        $columnKey->id = $this->dbConn->getLastInsertId();
        //DebugLog::log($columnKey->id);
    }
    //

    public function getById(int $columnKeyId): ?ColumnKeyModel {
        // TODO: Implement getById() method.
        return new ColumnKeyModel();
    }

    public function findBySchemaId(int $columnKeyId): ?ColumnKeyModel {
        // TODO: Implement getById() method.
        return new ColumnKeyModel();
    }

    public function save(ColumnKeyModel $columnKey): void {
        $data = ColumnKeyMapper::toDomain($columnKey);

        if($columnKey->id) {
            // Update
            /*$stmt = $this->db->prepare("UPDATE tbl_table_data SET ... WHERE id = :id");
            $stmt->execute($data);*/
        } else {
            // Insert
            /*$stmt = $this->db->prepare("INSERT INTO tbl_table_data (...) VALUES (...)");
            $stmt->execute($data);
            $columnKey->id = $this->db->lastInsertId();*/
        }
    }

    public function createOld(ColumnKeyModel $columnKey): void {
        $columnKeyTableName = "tbl_column_key";
        //DebugLog::log($tableData);
        $data = ColumnKeyMapper::toDomainParams($columnKey);
        //DebugLog::log($data);
        /*$stmt = $this->db->prepare("INSERT INTO tbl_table_data (...) VALUES (...)");
        $stmt->execute($data);*/
        //$sqlQuery = "INSERT INTO tbl_table_data (...) VALUES (...)";
        $tempTableData = new ColumnKey();
        $dataVarList = $tempTableData->getVarList();
        $columns = "";
        $values = "";
        foreach($dataVarList as $var) {
            $columns .= "$var, ";
            $values .= ":$var, ";
        }
        $columns = trim(trim($columns), ",");
        $values = trim(trim($values), ",");
        $sqlQuery = "INSERT INTO $columnKeyTableName ($columns) VALUES ($values)";
        DebugLog::log($sqlQuery);
        DebugLog::log($values);
        $this->dbConn->execute($sqlQuery, $data);
        $columnKey->id = $this->dbConn->getLastInsertId();
        //DebugLog::log($columnKey->id);
    }

    public function delete(int $id): void {
        // TODO: Implement delete() method.
    }
}
?>