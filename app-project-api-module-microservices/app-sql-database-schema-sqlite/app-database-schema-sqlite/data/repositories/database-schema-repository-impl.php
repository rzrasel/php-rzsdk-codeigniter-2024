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
        $data = DatabaseSchemaMapper::toDomain($schema);
        /*$stmt = $this->db->prepare("INSERT INTO tbl_database_schema (...) VALUES (...)");
        $stmt->execute($data);
        $schema->id = $this->db->lastInsertId();*/
        $sqlQuery = "INSERT INTO tbl_database_schema (...) VALUES (...)";
        $this->dbConn->execute($sqlQuery, $data);
    }

    public function save(DatabaseSchemaModel $schema): void {
        $data = DatabaseSchemaMapper::toDomain($schema);

        if ($schema->id) {
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