<?php
namespace App\Data\Repositories;
?>
<?php
use App\Config\Database;
use App\Domain\Repositories\DatabaseSchemaRepositoryInterface;
use App\Data\Entities\DatabaseSchema;
use App\Domain\Models\DatabaseSchemaModel;
use App\Data\Mappers\DatabaseSchemaMapper;
use PDO;
?>
<?php
class DatabaseSchemaRepositoryImpl implements DatabaseSchemaRepositoryInterface {
    private PDO $db;

    /*public function __construct(PDO $db) {
        $this->db = $db;
    }*/

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /*public function findById(int $id): ?DatabaseSchema {
        $stmt = $this->db->prepare("SELECT * FROM tbl_database_schema WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return DatabaseSchemaMapper::toDomain($data);
        }

        return null;
    }*/

    /*public function save(DatabaseSchema $schema): void {
        $data = DatabaseSchemaMapper::toData($schema);

        if ($schema->id) {
            // Update
            $stmt = $this->db->prepare("UPDATE tbl_database_schema SET ... WHERE id = :id");
            $stmt->execute($data);
        } else {
            // Insert
            $stmt = $this->db->prepare("INSERT INTO tbl_database_schema (...) VALUES (...)");
            $stmt->execute($data);
        }
    }*/

    public function create(DatabaseSchemaModel $schema): bool {
        //echo "<pre>" . print_r($schema, true) . "</pre>";
        $query = "INSERT INTO tbl_database_schema (id, schema_name, schema_version, table_prefix, modified_date, created_date) 
                  VALUES (:id, :schema_name, :schema_version, :table_prefix, :modified_date, :created_date)";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            'id' => $schema->id,
            'schema_name' => $schema->schemaName,
            'schema_version' => $schema->schemaVersion,
            'table_prefix' => $schema->tablePrefix,
            'modified_date' => $schema->modifiedDate,
            'created_date' => $schema->createdDate,
        ]);
    }

    public function getById(int $id): ?DatabaseSchemaModel {
        $stmt = $this->db->prepare("SELECT * FROM tbl_database_schema WHERE id = :id");
        $stmt->execute(['id' => $id]);
        //$row = $stmt->fetch(PDO::FETCH_ASSOC);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        //echo "<pre>" . print_r($row, true) . "</pre>";

        return DatabaseSchemaMapper::toDomainModel($row);
        //return DatabaseSchemaMapper::toDomainModel((object) $row);
    }

    public function findById(int $id): ?DatabaseSchemaModel {
        $stmt = $this->db->prepare("SELECT * FROM tbl_database_schema WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if($data) {
            return DatabaseSchemaMapper::toDomainModel($data);
        }

        return null;
    }

    public function save(DatabaseSchemaModel $schema, $isUpdate = false): void {
        // Convert Domain Model to Data Model
        $data = DatabaseSchemaMapper::toDomainDataArray($schema);
        echo "<pre>" . print_r($data, true) . "</pre>";

        if ($schema->id && $isUpdate) {
            // Update existing record
            $stmt = $this->db->prepare("
                UPDATE tbl_database_schema 
                SET schema_name = :schema_name, 
                    schema_version = :schema_version, 
                    table_prefix = :table_prefix, 
                    modified_date = :modified_date 
                WHERE id = :id
            ");
            $stmt->execute($data);
        } else {
            // Insert new record
            $stmt = $this->db->prepare("
                INSERT INTO tbl_database_schema 
                (id, schema_name, schema_version, table_prefix, modified_date, created_date) 
                VALUES (:id, :schema_name, :schema_version, :table_prefix, :modified_date, :created_date)
            ");
            $stmt->execute($data);
            //var_dump($stmt->errorInfo());

            // Set the ID of the newly inserted record
            $schema->id = $this->db->lastInsertId();
        }
    }

    public function delete(int $id): void {
        $stmt = $this->db->prepare("DELETE FROM tbl_database_schema WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
?>