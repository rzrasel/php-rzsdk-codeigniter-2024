<?php
namespace App\DatabaseSchema\Data\Repositories;
?>
<?php
use RzSDK\Database\SqliteConnection;
use App\DatabaseSchema\Domain\Repositories\CompositeKeyRepositoryInterface;
use App\DatabaseSchema\Data\Entities\CompositeKey;
use App\DatabaseSchema\Domain\Models\CompositeKeyModel;
use App\DatabaseSchema\Data\Mappers\TableDataMapper;
use RzSDK\Log\DebugLog;
use RzSDK\Log\LogType;
?>
<?php
class CompositeKeyRepositoryImpl implements CompositeKeyRepositoryInterface {
    private SqliteConnection $dbConn;

    public function __construct(SqliteConnection $dbConn = null) {
        if(is_null($dbConn)) {
            $this->dbConn = SqliteConnection::getInstance(DB_FULL_PATH);
        } else {
            $this->dbConn = $dbConn;
        }
    }

    public function getById(int $compositeKeyId): ?CompositeKeyModel {
        // TODO: Implement getById() method.
        return new CompositeKeyModel();
    }

    public function findBySchemaId(int $compositeKeyId): ?CompositeKeyModel {
        // TODO: Implement getById() method.
        return new CompositeKeyModel();
    }

    public function create(CompositeKeyModel $compositeKey): void {
        //DebugLog::log($tableData);
        $data = TableDataMapper::toDomainParams($compositeKey);
        //DebugLog::log($data);
        /*$stmt = $this->db->prepare("INSERT INTO tbl_table_data (...) VALUES (...)");
        $stmt->execute($data);*/
        //$sqlQuery = "INSERT INTO tbl_table_data (...) VALUES (...)";
        $tempTableData = new CompositeKey();
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
        $compositeKey->id = $this->dbConn->getLastInsertId();
        DebugLog::log($compositeKey->id);
    }

    public function save(CompositeKeyModel $compositeKey): void {
        $data = TableDataMapper::toDomain($compositeKey);

        if($compositeKey->id) {
            // Update
            $stmt = $this->db->prepare("UPDATE tbl_table_data SET ... WHERE id = :id");
            $stmt->execute($data);
        } else {
            // Insert
            $stmt = $this->db->prepare("INSERT INTO tbl_table_data (...) VALUES (...)");
            $stmt->execute($data);
            $compositeKey->id = $this->db->lastInsertId();
        }
    }

    public function delete(int $id): void {
        // TODO: Implement delete() method.
    }
}
?>