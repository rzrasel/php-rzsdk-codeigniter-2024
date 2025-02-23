<?php
namespace App\DatabaseSchema\Presentation\ViewModels;
?>
<?php
use App\DatabaseSchema\Data\Entities\ColumnData;
use App\DatabaseSchema\Domain\Models\ColumnDataModel;
use App\DatabaseSchema\Domain\Repositories\ColumnDataRepositoryInterface;
use RzSDK\Identification\UniqueIntId;
use RzSDK\Log\DebugLog;
?>
<?php
class ColumnDataViewModel {
    private $repository;

    public function __construct(ColumnDataRepositoryInterface $repository) {
        $this->repository = $repository;
    }
    public function getAllTableDataGroupBySchema(): array|bool {
        return $this->repository->getAllTableDataGroupBySchema();
    }

    public function createFromPostData($postData): void {
        //DebugLog::log($postData);
        $uniqueIntId = new UniqueIntId();
        $columnData = new ColumnData();
        $columnDataModel = new ColumnDataModel();
        //
        $columnName = trim($postData[$columnData->column_name]);
        $columnName = preg_replace("/\s+/", "_", $columnName);
        $columnName = preg_replace("-", "_", $columnName);
        //
        $postDataType = $postData["data_type"];
        $postDataLength = $postData["data_length"];
        $dataType = $postDataType;
        if($postDataLength > 0) {
            $dataType .= "($postDataLength)";
        }
        //
        $isNullable = $postData[$columnData->is_nullable];
        $haveDefault = $postData[$columnData->have_default];
        /*$isNullable = false;
        if($isNullable) {
            $isNullable = false;
        } else {
            $isNullable = true;
        }*/
        //
        $columnDataModel->id = $uniqueIntId->getId();
        $columnDataModel->tableId = $postData[$columnData->table_id];
        $columnDataModel->columnOrder = $postData[$columnData->column_order];
        $columnDataModel->columnName = $columnName;
        $columnDataModel->dataType = strtoupper($dataType);
        $columnDataModel->isNullable = $isNullable;
        $columnDataModel->haveDefault = $haveDefault;
        $columnDataModel->defaultValue = $postData[$columnData->default_value];
        $columnDataModel->columnComment = $postData[$columnData->column_comment];
        $columnDataModel->modifiedDate = date('Y-m-d H:i:s');
        $columnDataModel->createdDate = date('Y-m-d H:i:s');
        //DebugLog::log($columnDataModel);
        $this->repository->create($columnDataModel);
    }

    public function updateFromPostData($postData): void {
        //DebugLog::log($postData);
        if(!empty($postData["id"])) {
            $columnData = new ColumnData();
            $columnDataModel = new ColumnDataModel();
            //
            $columnName = trim($postData[$columnData->column_name]);
            $columnName = preg_replace("/\s+/", "_", $columnName);
            $columnName = preg_replace("/-/", "_", $columnName);
            //
            $postDataType = $postData["data_type"];
            $postDataLength = $postData["data_length"];
            $dataType = $postDataType;
            if($postDataLength > 0) {
                $dataType .= "($postDataLength)";
            }
            //
            $isNullable = $postData[$columnData->is_nullable];
            $haveDefault = $postData[$columnData->have_default];
            $columnDataModel->id = $postData[$columnData->id];
            $columnDataModel->tableId = $postData[$columnData->table_id];
            $columnDataModel->columnOrder = $postData[$columnData->column_order];
            $columnDataModel->columnName = $columnName;
            $columnDataModel->dataType = strtoupper($dataType);
            $columnDataModel->isNullable = $isNullable;
            $columnDataModel->haveDefault = $haveDefault;
            $columnDataModel->defaultValue = $postData[$columnData->default_value];
            $columnDataModel->columnComment = $postData[$columnData->column_comment];
            $columnDataModel->modifiedDate = date('Y-m-d H:i:s');
            $columnDataModel->createdDate = date('Y-m-d H:i:s');
            //DebugLog::log($columnDataModel);
            $this->repository->update($columnDataModel);
        } else {
            DebugLog::log("Data error");
        }
    }
    //

    public function getTable(int $id): ?ColumnDataModel {
        return $this->repository->getById($id);
    }

    public function insertTable(ColumnDataModel $columnData) {
        $this->repository->save($columnData);
    }

    public function createTable(ColumnDataModel $columnData): void {
        $this->repository->create($columnData);
    }
}
?>