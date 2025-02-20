<?php
namespace App\DatabaseSchema\Presentation\ViewModels;
?>
<?php
use App\DatabaseSchema\Data\Entities\CompositeKey;
use App\DatabaseSchema\Domain\Models\CompositeKeyModel;
use App\DatabaseSchema\Domain\Repositories\CompositeKeyRepositoryInterface;
use RzSDK\Identification\UniqueIntId;
use RzSDK\Log\DebugLog;
?>
<?php
class CompositeKeyViewModel {
    private $repository;

    public function __construct(CompositeKeyRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function getAllTableDataGroupByTable(): array|bool {
        return $this->repository->getAllTableDataGroupByTable();
    }

    public function createFromPostData($postData, ?array $schemaDataList = array()): void {
        $uniqueIntId = new UniqueIntId();
        $compositeKey = new CompositeKey();
        $compositeKeyModel = new CompositeKeyModel();
        //
        $compositeKeyModel->id = $uniqueIntId->getId();
        $compositeKeyModel->keyId = $postData[$compositeKey->key_id];
        $compositeKeyModel->primaryColumn = $postData[$compositeKey->primary_column];
        $compositeKeyModel->compositeColumn = $postData[$compositeKey->composite_column];
        $compositeKeyModel->keyName = $postData[$compositeKey->key_name];
        $compositeKeyModel->modifiedDate = date('Y-m-d H:i:s');
        $compositeKeyModel->createdDate = date('Y-m-d H:i:s');
        //
        //DebugLog::log($columnKeyModel);
        //
        $this->repository->create($compositeKeyModel);
    }
    //

    /*public function getTable(int $id): ?TableDataModel {
        return $this->repository->getById($id);
    }

    public function insertTable(TableDataModel $tableData) {
        $this->repository->save($tableData);
    }

    public function createTable(TableDataModel $tableData): void {
        $this->repository->create($tableData);
    }*/
}
?>