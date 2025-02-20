<?php
namespace App\DatabaseSchema\Data\Repositories;
?>
<?php
use RzSDK\Database\SqliteConnection;
use App\DatabaseSchema\Domain\Repositories\CompositeKeyRepositoryInterface;
use App\DatabaseSchema\Data\Entities\CompositeKey;
use App\DatabaseSchema\Domain\Models\CompositeKeyModel;
use App\DatabaseSchema\Data\Mappers\CompositeKeyMapper;
use App\DatabaseSchema\Helper\Database\Data\Retrieve\DbRetrieveDatabaseSchemaData;
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

    public function getAllTableDataGroupByTable(): array|bool {
        $dbDatabaseSchemaData = (new DbRetrieveDatabaseSchemaData())->getAllDatabaseSchemaData();
        return $dbDatabaseSchemaData;
    }

    public function create(CompositeKeyModel $compositeKey): void {
        $compositeKeyTableName = "tbl_composite_key";
        //DebugLog::log($tableData);
        $params = CompositeKeyMapper::toDomainParams($compositeKey);
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
        $sqlQuery = "INSERT INTO $compositeKeyTableName ($columns) VALUES ($values)";
        //DebugLog::log($sqlQuery);
        //DebugLog::log($values);
        //DebugLog::log($params);
        $this->dbConn->execute($sqlQuery, $params);
        $compositeKey->id = $this->dbConn->getLastInsertId();
        //DebugLog::log($columnKey->id);
    }
    //

    public function getById(int $compositeKeyId): ?CompositeKeyModel {
        // TODO: Implement getById() method.
        return new CompositeKeyModel();
    }

    public function findBySchemaId(int $compositeKeyId): ?CompositeKeyModel {
        // TODO: Implement getById() method.
        return new CompositeKeyModel();
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

    public function createOld(CompositeKeyModel $compositeKey): void {
        //DebugLog::log($tableData);
        $data = CompositeKeyMapper::toDomainParams($compositeKey);
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

    public function delete(int $id): void {
        // TODO: Implement delete() method.
    }
}
?>