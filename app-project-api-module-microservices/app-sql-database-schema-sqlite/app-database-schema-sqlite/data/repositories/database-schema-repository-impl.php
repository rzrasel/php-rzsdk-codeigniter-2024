<?php
namespace App\DatabaseSchema\Data\Repositories;
?>
<?php
use RzSDK\Log\DebugLog;
use RzSDK\Database\SqliteConnection;
use App\DatabaseSchema\Domain\Repositories\DatabaseSchemaRepositoryInterface;
use App\DatabaseSchema\Data\Entities\DatabaseSchema;
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use App\DatabaseSchema\Data\Mappers\DatabaseSchemaMapper;
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

    public function getById(int $id): ?DatabaseSchemaModel {
        // TODO: Implement getById() method.
        return new DatabaseSchemaModel();
    }

    public function create(DatabaseSchemaModel $schema): void {
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
        $sqlQuery = "INSERT INTO tbl_database_schema
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
}
?>