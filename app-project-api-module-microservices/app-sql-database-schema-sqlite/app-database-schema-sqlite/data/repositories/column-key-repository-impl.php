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
use App\DatabaseSchema\Helper\Database\Data\Retrieve\DbRetrieveDatabaseSchemaData;
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
        $dbDatabaseSchemaData = (new DbRetrieveDatabaseSchemaData())->getAllDatabaseSchemaData();
        return $dbDatabaseSchemaData;
    }

    public function create(ColumnKeyModel $columnKey): void {
        $columnKeyTableName = "tbl_column_key";
        //
        $colIdName = "id";
        $colIdValue = $columnKey->id;
        //
        if($this->dbConn->isDataExists($columnKeyTableName, $colIdName, $colIdValue)) {
            //$this->save($columnData, $colIdValue);
            DebugLog::log("Data already exists");
            return;
        }
        //
        $colWorkingTableName = "working_table";
        $colMainColumnName = "main_column";
        $colKeyTypeName = "key_type";
        $colWorkingTableValue = $columnKey->workingTable;
        $colMainColumnValue = $columnKey->mainColumn;
        $colKeyTypeValue = $columnKey->keyType;
        //
        $conditions = [
            $colWorkingTableName => $colWorkingTableValue,
            $colMainColumnName => $colMainColumnValue,
            $colKeyTypeName => $colKeyTypeValue,
        ];
        //
        if($this->dbConn->isDataExistsMultiple($columnKeyTableName, $conditions)) {
            //DebugLog::log("Ddddddddddd");
            //$this->save($columnData);
            DebugLog::log("Data already exists");
            return;
        }
        //
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