<?php
namespace App\DatabaseSchema\Data\Repositories;
?>
<?php
use RzSDK\Database\SqliteConnection;
use App\DatabaseSchema\Domain\Repositories\DatabaseSchemaRepositoryInterface;
use App\DatabaseSchema\Data\Entities\DatabaseSchema;
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use App\DatabaseSchema\Data\Mappers\DatabaseSchemaMapper;
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
        //$this->dbConn->connect();
        $params = DatabaseSchemaMapper::toDomainParams($schema);
        // Insert new record
        $sqlQuery = "INSERT INTO tbl_database_schema 
                (id, schema_name, schema_version, table_prefix, database_comment, modified_date, created_date) 
                VALUES (:id, :schema_name, :schema_version, :table_prefix, :database_comment, :modified_date, :created_date)";
        $this->dbConn->execute($sqlQuery, $params);
        //var_dump($stmt->errorInfo());

        // Set the ID of the newly inserted record
        $schema->id = $this->dbConn->getLastInsertId();
    }

    public function save(DatabaseSchemaModel $schema): void {
        // TODO: Implement save() method.
    }

    public function delete(int $id): void {
        // TODO: Implement delete() method.
    }
}
?>