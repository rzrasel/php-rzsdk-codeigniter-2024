<?php
namespace App\DatabaseSchema\Data\Repositories;
?>
<?php
use RzSDK\Database\SqliteConnection;
use App\DatabaseSchema\Domain\Repositories\DatabaseSchemaRepositoryInterface;
use App\DatabaseSchema\Data\Entities\DatabaseSchema;
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use App\DatabaseSchema\Data\Mappers\DatabaseSchemaMapper;
use App\DatabaseSchema\Data\Mappers\TableDataMapper;
use App\DatabaseSchema\Data\Mappers\ColumnDataMapper;
use RzSDK\Log\DebugLog;
use RzSDK\Log\LogType;
?>
<?php
class DatabaseSchemaRepositoryImpl implements DatabaseSchemaRepositoryInterface {
    private SqliteConnection $dbConn;

    public function __construct(SqliteConnection $dbConn = null) {
        if(is_null($dbConn)) {
            $this->dbConn = SqliteConnection::getInstance(DB_FULL_PATH);
        } else {
            $this->dbConn = $dbConn;
        }
    }

    public function getAllData(): array|bool {
        $schemaTableName = "tbl_database_schema";
        $databaseSchemaList = array();
        $sqlQuery = "SELECT * FROM $schemaTableName;";
        //DebugLog::log($sqlQuery);
        $results = $this->dbConn->query($sqlQuery);
        foreach($results as $result) {
            //DebugLog::log($result);
            $databaseSchema = DatabaseSchemaMapper::toModel($result);
            //DebugLog::log($databaseSchema);
            $tableDataList = $this->getAllTableData($databaseSchema->id);
            if($tableDataList) {
                $databaseSchema->tableDataList = $tableDataList;
            }
            $databaseSchemaList[] = $databaseSchema;
        }
        //DebugLog::log($results);
        if(!empty($databaseSchemaList)) {
            return $databaseSchemaList;
        }
        return false;
    }

    public function getById(int $id): ?DatabaseSchemaModel {
        // TODO: Implement getById() method.
        return new DatabaseSchemaModel();
    }

    public function create(DatabaseSchemaModel $schema): void {
        $schemaTableName = "tbl_database_schema";
        $columnName = "id";
        $value = $schema->id;
        if($this->dbConn->isDataExists($schemaTableName, $columnName, $value)) {
            //$this->save($schema);
            return;
        }
        $columnName = "schema_name";
        $value = $schema->schemaName;
        if($this->dbConn->isDataExists($schemaTableName, $columnName, $value)) {
            //$this->save($schema);
            return;
        }
        /*$data = DatabaseSchemaMapper::toEntity($schema);
        $data = DatabaseSchemaMapper::toData($data);*/
        $params = DatabaseSchemaMapper::toDomainParams($schema);
        /*$databaseSchema = DatabaseSchemaMapper::toDomainToEntity($schema);
        $databaseSchema = $databaseSchema->getVarList();*/
        //DebugLog::log($params, LogType::INFORMATION);
        /*$stmt = $this->db->prepare("INSERT INTO tbl_database_schema (...) VALUES (...)");
        $stmt->execute($params);
        $schema->id = $this->db->lastInsertId();*/
        //$sqlQuery = "INSERT INTO tbl_database_schema (...) VALUES (...)";
        $sqlQuery = "INSERT INTO $schemaTableName
                (id, schema_name, schema_version, table_prefix, database_comment, modified_date, created_date) 
                VALUES (:id, :schema_name, :schema_version, :table_prefix, :database_comment, :modified_date, :created_date)";
        $tempDatabaseSchema = new DatabaseSchema();
        $dataVarList = $tempDatabaseSchema->getVarList();
        $columns = "";
        $values = "";
        foreach($dataVarList as $var) {
            $columns .= "$var, ";
            $values .= ":$var, ";
        }
        $columns = trim(trim($columns), ",");
        $values = trim(trim($values), ",");
        $sqlQuery = "INSERT INTO tbl_database_schema ($columns) VALUES ($values)";
        //DebugLog::log($sqlQuery);
        $this->dbConn->execute($sqlQuery, $params);
    }

    public function save(DatabaseSchemaModel $schema): void {
        $data = DatabaseSchemaMapper::toDomain($schema);
        //echo "<pre>" . print_r($data, true) . "</pre>";

        if($schema->id) {
            // Update
            $stmt = $this->db->prepare("UPDATE tbl_database_schema SET ... WHERE id = :id");
            $stmt->execute($data);
        } else {
            // Insert
            $stmt = $this->db->prepare("INSERT INTO tbl_database_schema (...) VALUES (...)");
            $stmt->execute($data);
            $schema->id = $this->db->lastInsertId();
        }
    }

    public function delete(int $id): void {
        // TODO: Implement delete() method.
    }

    public function getAllTableData($schemaId): array|bool {
        $tableDataName = "tbl_table_data";
        $tableDataList = array();
        $sqlQuery = "SELECT * FROM $tableDataName WHERE schema_id = '$schemaId';";
        //DebugLog::log($sqlQuery);
        $results = $this->dbConn->query($sqlQuery);
        foreach($results as $result) {
            //DebugLog::log($result);
            $tableData = TableDataMapper::toModel($result);
            //DebugLog::log($tableData);
            $columnDataList = $this->getAllColumnData($tableData->id);
            if($columnDataList) {
                $tableData->columnDataList = $columnDataList;
            }
            $tableDataList[] = $tableData;
        }
        //DebugLog::log($results);
        if(!empty($tableDataList)) {
            return $tableDataList;
        }
        return false;
    }

    public function getAllColumnData($tableId): array|bool {
        $columnDataName = "tbl_column_data";
        $columnDataList = array();
        $sqlQuery = "SELECT * FROM $columnDataName WHERE table_id = '$tableId';";
        //DebugLog::log($sqlQuery);
        $results = $this->dbConn->query($sqlQuery);
        foreach($results as $result) {
            //DebugLog::log($result);
            $columnData = ColumnDataMapper::toModel($result);
            //DebugLog::log($columnData);
            $columnDataList[] = $columnData;
        }
        //DebugLog::log($results);
        if(!empty($columnDataList)) {
            return $columnDataList;
        }
        return false;
    }
}
?>