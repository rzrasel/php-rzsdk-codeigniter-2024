<?php
namespace App\DatabaseSchema\Data\Repositories;
?>
<?php
use RzSDK\Database\SqliteConnection;
use App\DatabaseSchema\Domain\Repositories\ColumnDataRepositoryInterface;
use App\DatabaseSchema\Data\Entities\ColumnData;
use App\DatabaseSchema\Domain\Models\ColumnDataModel;
use App\DatabaseSchema\Data\Mappers\ColumnDataMapper;
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

    public function getById(int $columnDataId): ?ColumnDataModel {
        // TODO: Implement getById() method.
        return new ColumnDataModel();
    }

    public function findBySchemaId(int $columnDataId): ?ColumnDataModel {
        // TODO: Implement getById() method.
        return new ColumnDataModel();
    }

    public function create(ColumnDataModel $columnData): void {
        $columnTableName = "tbl_column_data";
        $colIdName = "id";
        $colIdValue = $columnData->id;
        if($this->dbConn->isDataExists($columnTableName, $colIdName, $colIdValue)) {
            $this->save($columnData, $colIdValue);
            return;
        }
        $colTableIdName = "table_id";
        $colColumnName = "column_name";
        $colTableIdValue = $columnData->tableId;
        $colColumnNameValue = $columnData->columnName;
        $conditions = [
            $colTableIdName => $colTableIdValue,
            $colColumnName => $colColumnNameValue,
        ];
        if($this->dbConn->isDataExistsMultiple($columnTableName, $conditions)) {
            //DebugLog::log("Ddddddddddd");
            $this->save($columnData);
            return;
        }
        //DebugLog::log($tableData);
        $data = ColumnDataMapper::toDomainParams($columnData);
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
        $sqlQuery = "INSERT INTO $columnTableName ($columns) VALUES ($values)";
        //DebugLog::log($sqlQuery);
        $this->dbConn->execute($sqlQuery, $data);
        $columnData->id = $this->dbConn->getLastInsertId();
        //DebugLog::log($columnData->id);
    }

    public function save(ColumnDataModel $columnData, $columnId = -1): void {
        $data = ColumnDataMapper::toDomain($columnData);

        if($columnData->id) {
            // Update
            $columnTableName = "tbl_column_data";
            if($columnId > 0) {
                $sqlQuery = "UPDATE $columnTableName SET column_name = :column_name, data_type = :data_type, is_nullable = :is_nullable, default_value = :default_value, column_comment = :column_comment, modified_date = :modified_date WHERE id = :id";
                $data = array(
                    ":id" => $columnData->id,
                    ":column_name" => $columnData->columnName,
                    ":data_type" => $columnData->dataType,
                    ":is_nullable" => $columnData->isNullable,
                    ":default_value" => $columnData->defaultValue,
                    ":column_comment" => $columnData->columnComment,
                    ":modified_date" => $columnData->modifiedDate,
                );
            } else {
                $sqlQuery = "UPDATE $columnTableName SET data_type = :data_type, is_nullable = :is_nullable, default_value = :default_value, column_comment = :column_comment, modified_date = :modified_date WHERE table_id = :table_id AND column_name = :column_name";
                $data = array(
                    ":table_id" => $columnData->tableId,
                    ":column_name" => $columnData->columnName,
                    ":data_type" => $columnData->dataType,
                    ":is_nullable" => $columnData->isNullable,
                    ":default_value" => $columnData->defaultValue,
                    ":column_comment" => $columnData->columnComment,
                    ":modified_date" => $columnData->modifiedDate,
                );
            }
            //DebugLog::log($sqlQuery);
            //DebugLog::log($data);
            $this->dbConn->execute($sqlQuery, $data);
        } else {
            // Insert
            /*$stmt = $this->db->prepare("INSERT INTO tbl_table_data (...) VALUES (...)");
            $stmt->execute($data);
            $columnData->id = $this->db->lastInsertId();*/
        }
    }

    public function delete(int $id): void {
        // TODO: Implement delete() method.
    }
}
?>