<?php
namespace App\DatabaseSchema\Presentation\ViewModels;
?>
<?php
use App\DatabaseSchema\Data\Entities\TableData;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use App\DatabaseSchema\Domain\Repositories\TableDataRepositoryInterface;
use RzSDK\Identification\UniqueIntId;
use RzSDK\Log\DebugLog;
?>
<?php
class TableDataViewModel {
    private $repository;

    public function __construct(TableDataRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function getAllDatabaseSchemaData(): array|bool {
        return $this->repository->getAllDatabaseSchemaData();
    }
    public function createFromPostData($postData): void {
        $uniqueIntId = new UniqueIntId();
        $tempTableData = new TableData();
        $tableDataModelModel = new TableDataModel();
        $tableDataModelModel->id = $uniqueIntId->getId();
        $tableDataModelModel->schemaId = $postData[$tempTableData->schema_id];
        $tableDataModelModel->tableName = $postData[$tempTableData->table_name];
        $tableDataModelModel->columnPrefix = $postData[$tempTableData->column_prefix];
        $tableDataModelModel->tableComment = $postData[$tempTableData->table_comment];
        $tableDataModelModel->modifiedDate = date('Y-m-d H:i:s');
        $tableDataModelModel->createdDate = date('Y-m-d H:i:s');
        $this->repository->create($tableDataModelModel);
    }
    //

    public function getTable(int $id): ?TableDataModel {
        return $this->repository->getById($id);
    }

    public function insertTable(TableDataModel $tableData) {
        $this->repository->save($tableData);
    }

    public function createTable(TableDataModel $tableData): void {
        $this->repository->create($tableData);
    }
}
?>