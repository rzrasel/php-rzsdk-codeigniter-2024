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
        $columnDataModel->id = $uniqueIntId->getId();
        $columnDataModel->tableId = $postData[$columnData->table_id];
        $columnDataModel->columnName = $postData[$columnData->column_name];
        $columnDataModel->dataType = strtoupper($postData[$columnData->data_type]);
        $columnDataModel->isNullable = $postData[$columnData->is_nullable];
        $columnDataModel->defaultValue = $postData[$columnData->default_value];
        $columnDataModel->columnComment = $postData[$columnData->column_comment];
        $columnDataModel->modifiedDate = date('Y-m-d H:i:s');
        $columnDataModel->createdDate = date('Y-m-d H:i:s');
        //DebugLog::log($columnDataModel);
        $this->repository->create($columnDataModel);
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