<?php
namespace App\DatabaseSchema\Data\Repositories;
?>
<?php
use RzSDK\Database\SqliteConnection;
use App\DatabaseSchema\Domain\Repositories\ColumnDataRepositoryInterface;
use App\DatabaseSchema\Data\Entities\ColumnData;
use App\DatabaseSchema\Domain\Models\ColumnDataModel;
use App\DatabaseSchema\Data\Mappers\TableDataMapper;
use RzSDK\Log\DebugLog;
use RzSDK\Log\LogType;
?>
<?php
class ColumnDataRepositoryImpl implements ColumnDataRepositoryInterface {
    private SqliteConnection $dbConn;

    public function __construct(SqliteConnection $dbConn = null) {
        if(is_null($dbConn)) {
            $this->dbConn = SqliteConnection::getInstance(DB_FULL_PATH);
        } else {
            $this->dbConn = $dbConn;
        }
    }

    public function getById(int $columnDataId): ?ColumnData {
        // TODO: Implement getById() method.
        return new ColumnData();
    }

    public function findBySchemaId(int $columnDataId): ?ColumnData {
        // TODO: Implement getById() method.
        return new ColumnData();
    }

    public function create(ColumnDataModel $columnData): void {
        //DebugLog::log($tableData);
        $data = TableDataMapper::toDomainParams($columnData);
        //DebugLog::log($data);
        /*$stmt = $this->db->prepare("INSERT INTO tbl_table_data (...) VALUES (...)");
        $stmt->execute($data);*/
        //$sqlQuery = "INSERT INTO tbl_table_data (...) VALUES (...)";
        $tempTableData = new ColumnData();
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
        $columnData->id = $this->dbConn->getLastInsertId();
        DebugLog::log($columnData->id);
    }

    public function save(ColumnDataModel $columnData): void {
        $data = TableDataMapper::toDomain($columnData);

        if($columnData->id) {
            // Update
            $stmt = $this->db->prepare("UPDATE tbl_table_data SET ... WHERE id = :id");
            $stmt->execute($data);
        } else {
            // Insert
            $stmt = $this->db->prepare("INSERT INTO tbl_table_data (...) VALUES (...)");
            $stmt->execute($data);
            $columnData->id = $this->db->lastInsertId();
        }
    }

    public function delete(int $id): void {
        // TODO: Implement delete() method.
    }
}
?>