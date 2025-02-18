<?php
namespace App\DatabaseSchema\Data\Repositories;
?>
<?php
use RzSDK\Database\SqliteConnection;
use App\DatabaseSchema\Domain\Repositories\ColumnKeyRepositoryInterface;
use App\DatabaseSchema\Data\Entities\ColumnKey;
use App\DatabaseSchema\Domain\Models\ColumnKeyModel;
use App\DatabaseSchema\Data\Mappers\CompositeKeyMapper;
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

    public function getById(int $columnKeyId): ?ColumnKeyModel {
        // TODO: Implement getById() method.
        return new ColumnKeyModel();
    }

    public function findBySchemaId(int $columnKeyId): ?ColumnKeyModel {
        // TODO: Implement getById() method.
        return new ColumnKeyModel();
    }

    public function create(ColumnKeyModel $columnKey): void {
        //DebugLog::log($tableData);
        $data = CompositeKeyMapper::toDomainParams($columnKey);
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
        $sqlQuery = "INSERT INTO tbl_table_data ($columns) VALUES ($values)";
        //DebugLog::log($sqlQuery);
        $this->dbConn->execute($sqlQuery, $data);
        $columnKey->id = $this->dbConn->getLastInsertId();
        DebugLog::log($columnKey->id);
    }

    public function save(ColumnKeyModel $columnKey): void {
        $data = TableDataMapper::toDomain($columnKey);

        if($columnKey->id) {
            // Update
            $stmt = $this->db->prepare("UPDATE tbl_table_data SET ... WHERE id = :id");
            $stmt->execute($data);
        } else {
            // Insert
            $stmt = $this->db->prepare("INSERT INTO tbl_table_data (...) VALUES (...)");
            $stmt->execute($data);
            $columnKey->id = $this->db->lastInsertId();
        }
    }

    public function delete(int $id): void {
        // TODO: Implement delete() method.
    }
}
?>