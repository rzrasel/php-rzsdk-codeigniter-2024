<?php
namespace App\DatabaseSchema\Data\Repositories;
?>
<?php
use RzSDK\Database\SqliteConnection;
use App\DatabaseSchema\Domain\Repositories\TableDataRepositoryInterface;
use App\DatabaseSchema\Data\Entities\DatabaseSchema;
use App\DatabaseSchema\Data\Entities\TableData;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use App\DatabaseSchema\Data\Mappers\DatabaseSchemaMapper;
use App\DatabaseSchema\Data\Mappers\TableDataMapper;
use RzSDK\Log\DebugLog;
use RzSDK\Log\LogType;
?>
<?php
class TableDataRepositoryImpl implements TableDataRepositoryInterface {
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
        $sqlQuery = "SELECT * FROM $schemaTableName ORDER BY {$tempDatabaseSchema->schema_name} ASC;";
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

    public function getById(int $tableDataId): ?TableDataModel {
        // TODO: Implement getById() method.
        return new TableDataModel();
    }

    public function findBySchemaId(int $tableDataId): ?TableDataModel {
        // TODO: Implement getById() method.
        return new TableDataModel();
    }

    public function create(TableDataModel $tableData): void {
        $tableName = "tbl_table_data";
        $colIdName = "id";
        $colIdValue = $tableData->id;
        if($this->dbConn->isDataExists($tableName, $colIdName, $colIdValue)) {
            $this->save($tableData);
            return;
        }
        $colTableName = "table_name";
        $colTableNameValue = $tableData->tableName;
        if($this->dbConn->isDataExists($tableName, $colTableName, $colTableNameValue)) {
            $this->save($tableData);
            return;
        }
        //DebugLog::log($tableData);
        $data = TableDataMapper::toDomainParams($tableData);
        //DebugLog::log($data);
        /*$stmt = $this->db->prepare("INSERT INTO tbl_table_data (...) VALUES (...)");
        $stmt->execute($data);*/
        //$sqlQuery = "INSERT INTO tbl_table_data (...) VALUES (...)";
        $tempTableData = new TableData();
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
        $tableData->id = $this->dbConn->getLastInsertId();
        //DebugLog::log($tableData->id);
    }

    public function save(TableDataModel $tableData): void {
        //$data = TableDataMapper::toDomain($tableData);
        //DebugLog::log($tableData);
        $tableName = "tbl_table_data";

        if($tableData->id) {
            // Update
            /*$stmt = $this->db->prepare("UPDATE tbl_table_data SET ... WHERE id = :id");
            $stmt->execute($data);*/
            //$data = TableDataMapper::toDomainParams($tableData);
            $sqlQuery = "UPDATE $tableName SET table_name = :table_name, table_comment = :table_comment, column_prefix = :column_prefix, modified_date = :modified_date WHERE id = :id OR table_name = :table_name";
            $data = array(
                ":id" => $tableData->id,
                ":table_name" => $tableData->tableName,
                ":table_comment" => $tableData->tableComment,
                ":column_prefix" => $tableData->columnPrefix,
                ":modified_date" => $tableData->modifiedDate,
            );
            //DebugLog::log($sqlQuery);
            //DebugLog::log($data);
            $this->dbConn->execute($sqlQuery, $data);
        } else {
            // Insert
            /*$stmt = $this->db->prepare("INSERT INTO tbl_table_data (...) VALUES (...)");
            $stmt->execute($data);
            $tableData->id = $this->db->lastInsertId();*/
        }
    }

    public function delete(int $id): void {
        // TODO: Implement delete() method.
    }
}
?>