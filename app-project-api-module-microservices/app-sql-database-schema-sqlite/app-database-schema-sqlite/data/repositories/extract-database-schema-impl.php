<?php
namespace App\DatabaseSchema\Data\Repositories;
?>
<?php
use App\DatabaseSchema\Domain\Repositories\ExtractDatabaseSchemaInterface;
use App\DatabaseSchema\Data\Entities\DatabaseSchema;
use App\DatabaseSchema\Data\Entities\TableData;
use App\DatabaseSchema\Data\Entities\ColumnData;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use App\DatabaseSchema\Domain\Models\ColumnDataModel;
use App\DatabaseSchema\Domain\Models\ColumnKeyModel;
use App\DatabaseSchema\Data\Mappers\DatabaseSchemaMapper;
use App\DatabaseSchema\Data\Mappers\TableDataMapper;
use App\DatabaseSchema\Data\Mappers\ColumnDataMapper;
use RzSDK\Database\SqliteConnection;
use RzSDK\Database\SqliteFetchType;
use RzSDK\Log\DebugLog;
?>
<?php
class ExtractDatabaseSchemaImpl implements ExtractDatabaseSchemaInterface {
    private SqliteConnection $dbConn;

    public function __construct(SqliteConnection $dbConn = null) {
        if(is_null($dbConn)) {
            $this->dbConn = SqliteConnection::getInstance(DB_FULL_PATH);
        } else {
            $this->dbConn = $dbConn;
        }
    }

    public function getAllDatabaseSchemaData(): array|bool {
        $schemaTableName = "tbl_database_schema";
        $tempDatabaseSchema = new DatabaseSchema();
        $databaseSchemaList = array();
        $sqlQuery = "SELECT * FROM {$schemaTableName} ORDER BY {$tempDatabaseSchema->schema_name} ASC;";
        $results = $this->dbConn->query($sqlQuery);
        foreach($results as $result) {
            $databaseSchema = DatabaseSchemaMapper::toModel($result);
            $databaseSchemaList[] = $databaseSchema;
        }
        if(!empty($databaseSchemaList)) {
            return $databaseSchemaList;
        }
        return false;
    }

    public function onInsertTableData(TableDataModel $tableDataModel): TableDataModel {
        //DebugLog::log($tableDataModel);
        $tableName = "tbl_table_data";
        //$tableDataModel = new TableDataModel();
        $dbTableData = $this->getIsTableExistsByName($tableDataModel->schemaId, $tableDataModel->tableName);
        //DebugLog::log($dbTableData);
        if(!empty($dbTableData)) {
            return TableDataMapper::toModel($dbTableData);
        }
        $tempTableData = new TableData();
        $tempTableData->setVars();
        $nextOrder = $this->getMaxDbTableOrder($tableDataModel->schemaId);
        $tableDataModel->tableOrder = $nextOrder;
        $data = TableDataMapper::toDomainParams($tableDataModel);
        //DebugLog::log($data);
        $dataVarList = $tempTableData->getVarList();
        $columns = "";
        $values = "";
        foreach($dataVarList as $var) {
            $columns .= "$var, ";
            $values .= ":$var, ";
        }
        $columns = trim(trim($columns), ",");
        $values = trim(trim($values), ",");
        $sqlQuery = "INSERT INTO $tableName ($columns) VALUES ($values)";
        //DebugLog::log($sqlQuery);
        $this->dbConn->execute($sqlQuery, $data);
        //
        //DebugLog::log($tableDataModel);
        return $tableDataModel;
    }

    public function getIsTableExistsByName(string $schemaId, string $tableName): ?TableData {
        $dbTableName = "tbl_table_data";
        $tempTableData = new TableData();
        $tempTableData->setVars();
        $schemaTableData = new TableData();
        $schemaTableData->getEmtyObject();
        $sqlQuery = "SELECT * FROM {$dbTableName} WHERE {$tempTableData->schema_id} = {$schemaId} AND {$tempTableData->table_name} = '{$tableName}';";
        //DebugLog::log($sqlQuery);
        $results = $this->dbConn->query($sqlQuery);
        //$results = $this->dbConn->fetch($results, SqliteFetchType::FETCH_OBJ);
        //DebugLog::log($results);
        //$schemaTableData = TableDataMapper::toEntity($results);
        $counter = 0;
        foreach($results as $result) {
            //DebugLog::log($result);
            $counter++;
            $schemaTableData = TableDataMapper::toEntity($result);
        }
        if($counter <= 0) {
            return null;
        }
        //DebugLog::log($schemaTableData);
        return $schemaTableData;
    }

    public function getMaxDbTableOrder(string $schemaId): int {
        $dbTableName = "tbl_table_data";
        $tempTableData = new TableData();
        $tempTableData->setVars();
        $sqlQuery = "SELECT MAX({$tempTableData->table_order}) AS {$tempTableData->table_order} FROM {$dbTableName} WHERE {$tempTableData->schema_id} = {$schemaId};";
        //DebugLog::log($sqlQuery);
        $results = $this->dbConn->query($sqlQuery, SqliteFetchType::FETCH_OBJ);
        //DebugLog::log($results);
        if(!empty($results)) {
            $value = $results->{$tempTableData->table_order};
            if($value > 0) {
                return $value + 1;
            }
        }
        return 1;
    }

    public function onInsertColumnData(ColumnDataModel $columnDataModel): ColumnDataModel {
        //DebugLog::log($columnDataModel);
        $tableName = "tbl_column_data";
        $dbColumnData = $this->getIsColumnExistsByName($columnDataModel->tableId, $columnDataModel->columnName);
        //DebugLog::log($dbColumnData);
        if(!empty($dbColumnData)) {
            return ColumnDataMapper::toModel($dbColumnData);
        }
        $tempColumnData = new ColumnData();
        $tempColumnData->setVars();
        $nextOrder = $this->getMaxDbColumnOrder($columnDataModel->tableId);
        $columnDataModel->columnOrder = $nextOrder;
        //DebugLog::log($columnDataModel);
        $data = ColumnDataMapper::toDomainParams($columnDataModel);
        //DebugLog::log($data);
        $dataVarList = $tempColumnData->getVarList();
        $columns = "";
        $values = "";
        foreach($dataVarList as $var) {
            $columns .= "$var, ";
            $values .= ":$var, ";
        }
        $columns = trim(trim($columns), ",");
        $values = trim(trim($values), ",");
        $sqlQuery = "INSERT INTO $tableName ($columns) VALUES ($values)";
        //DebugLog::log($sqlQuery);
        $this->dbConn->execute($sqlQuery, $data);
        //
        //DebugLog::log($tableDataModel);
        return $columnDataModel;
    }

    public function getIsColumnExistsByName(string $tableId, string $columnName): ?ColumnData {
        $dbTableName = "tbl_column_data";
        $tempColumnData = new ColumnData();
        $tempColumnData->setVars();
        $schemaColumnData = new ColumnData();
        $sqlQuery = "SELECT * FROM {$dbTableName} WHERE {$tempColumnData->table_id} = {$tableId} AND {$tempColumnData->column_name} = '{$columnName}';";
        //DebugLog::log($sqlQuery);
        $results = $this->dbConn->query($sqlQuery);
        //$results = $this->dbConn->fetch($results, SqliteFetchType::FETCH_OBJ);
        //DebugLog::log($results);
        //$schemaColumnData = ColumnDataMapper::toEntity($results);
        $counter = 0;
        foreach($results as $result) {
            //DebugLog::log($result);
            $counter++;
            $schemaColumnData = ColumnDataMapper::toEntity($result);
        }
        //DebugLog::log($counter);
        if($counter <= 0) {
            return null;
        }
        //DebugLog::log($schemaColumnData);
        return $schemaColumnData;
    }

    public function getMaxDbColumnOrder(string $tableId): int {
        $dbTableName = "tbl_column_data";
        $tempColumnData = new ColumnData();
        $tempColumnData->setVars();
        $sqlQuery = "SELECT MAX({$tempColumnData->column_order}) AS {$tempColumnData->column_order} FROM {$dbTableName} WHERE {$tempColumnData->table_id} = {$tableId};";
        //DebugLog::log($sqlQuery);
        $results = $this->dbConn->query($sqlQuery, SqliteFetchType::FETCH_OBJ);
        //DebugLog::log($results);
        if(!empty($results)) {
            $value = $results->{$tempColumnData->column_order};
            if($value > 0) {
                return $value + 1;
            }
        }
        return 1;
    }

    public function onInsertColumnKey($schemaId, ColumnKeyModel $columnKeyModel): ColumnKeyModel {
        return $columnKeyModel;
    }
}
?>