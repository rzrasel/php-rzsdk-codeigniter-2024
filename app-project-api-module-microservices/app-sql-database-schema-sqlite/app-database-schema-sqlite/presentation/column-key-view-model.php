<?php
namespace App\DatabaseSchema\Presentation\ViewModels;
?>
<?php
use App\DatabaseSchema\Data\Entities\ColumnKey;
use App\DatabaseSchema\Domain\Models\ColumnKeyModel;
use App\DatabaseSchema\Domain\Repositories\ColumnKeyRepositoryInterface;
use RzSDK\Identification\UniqueIntId;
use RzSDK\Log\DebugLog;

?>
<?php
class ColumnKeyViewModel {
    private $repository;

    public function __construct(ColumnKeyRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function getAllTableDataGroupByTable(): array|bool {
        return $this->repository->getAllTableDataGroupByTable();
    }

    public function createFromPostData($postData): void {
        //DebugLog::log($postData);
        $uniqueIntId = new UniqueIntId();
        $columnKey = new ColumnKey();
        $columnKeyModel = new ColumnKeyModel();
        //
        $columnKeyModel->id = $uniqueIntId->getId();
        $columnKeyModel->mainColumn = $postData[$columnKey->main_column];
        $columnKeyModel->keyType = $postData[$columnKey->key_type];
        $columnKeyModel->referenceColumn = $postData[$columnKey->reference_column];
        $columnKeyModel->keyName = $postData[$columnKey->key_name];
        $columnKeyModel->uniqueName = $postData[$columnKey->unique_name];
        $columnKeyModel->modifiedDate = date('Y-m-d H:i:s');
        $columnKeyModel->createdDate = date('Y-m-d H:i:s');
        //
        //DebugLog::log($columnKeyModel);
        //
        $this->repository->create($columnKeyModel);
    }
    //

    public function getTable(int $id): ?ColumnKeyModel {
        return $this->repository->getById($id);
    }

    public function insertTable(ColumnKeyModel $columnKey) {
        $this->repository->save($columnKey);
    }

    public function createTable(ColumnKeyModel $columnKey): void {
        $this->repository->create($columnKey);
    }
}
?>