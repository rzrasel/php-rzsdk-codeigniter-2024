<?php
namespace App\DatabaseSchema\Data\Repositories;
?>
<?php
use RzSDK\Database\SqliteConnection;
use App\DatabaseSchema\Domain\Repositories\TableDataRepositoryInterface;
use App\DatabaseSchema\Data\Entities\TableData;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use App\DatabaseSchema\Data\Mappers\TableDataMapper;
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

    public function getById(int $schemaId): ?TableDataModel {
        // TODO: Implement getById() method.
        return new TableDataModel();
    }

    public function findBySchemaId(int $schemaId): ?TableDataModel {
        // TODO: Implement getById() method.
        return new TableDataModel();
    }

    public function create(TableDataModel $tableData): void {
        $data = TableDataMapper::toDomain($tableData);
        /*$stmt = $this->db->prepare("INSERT INTO tbl_table_data (...) VALUES (...)");
        $stmt->execute($data);*/
        $sqlQuery = "INSERT INTO tbl_table_data (...) VALUES (...)";
        $this->dbConn->execute($sqlQuery, $data);
        $tableData->id = $this->db->lastInsertId();
    }

    public function save(TableDataModel $tableData): void {
        $data = TableDataMapper::toDomain($tableData);

        if ($tableData->id) {
            // Update
            $stmt = $this->db->prepare("UPDATE tbl_table_data SET ... WHERE id = :id");
            $stmt->execute($data);
        } else {
            // Insert
            $stmt = $this->db->prepare("INSERT INTO tbl_table_data (...) VALUES (...)");
            $stmt->execute($data);
            $tableData->id = $this->db->lastInsertId();
        }
    }

    public function delete(int $id): void {
        // TODO: Implement delete() method.
    }
}
?>