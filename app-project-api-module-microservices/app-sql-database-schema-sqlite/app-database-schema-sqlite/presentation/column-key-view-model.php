<?php
namespace App\DatabaseSchema\Presentation\ViewModels;
?>
<?php
use App\DatabaseSchema\Data\Entities\ColumnKey;
use App\DatabaseSchema\Domain\Models\ColumnKeyModel;
use App\DatabaseSchema\Domain\Repositories\ColumnKeyRepositoryInterface;
use App\DatabaseSchema\Schema\Unique\Name\SchemaUniqueNameGenerator;
use App\DatabaseSchema\Helper\Key\Type\RelationalKeyType;
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

    public function createFromPostData($postData, ?array $schemaDataList = array()): void {
        //DebugLog::log($postData);
        $uniqueIntId = new UniqueIntId();
        $columnKey = new ColumnKey();
        $columnKeyModel = new ColumnKeyModel();
        //SchemaUniqueNameGenerator
        $primaryColumnId = $postData[$columnKey->main_column];
        $referenceColumnId = $postData[$columnKey->reference_column];
        $keyType = $postData[$columnKey->key_type];
        $uniqueName = $postData[$columnKey->unique_name];
        if(!empty($schemaDataList)) {
            $uniqueName = $this->getRelationalKeyUniqueName($schemaDataList, $keyType, $primaryColumnId, $referenceColumnId);
        }
        //
        $primaryColumnInfo = SchemaUniqueNameGenerator::columnNameUniqueText($schemaDataList, $primaryColumnId);
        $workingSchemaIndex = $primaryColumnInfo["index"]["schema"];
        $workingTableIndex = $primaryColumnInfo["index"]["table"];
        $workingTableId = $schemaDataList[$workingSchemaIndex]->tableDataList[$workingTableIndex]->id;
        //
        $columnKeyModel->id = $uniqueIntId->getId();
        $columnKeyModel->workingTable = $workingTableId;
        $columnKeyModel->mainColumn = $primaryColumnId;
        $columnKeyModel->keyType = $keyType;
        $columnKeyModel->referenceColumn = $referenceColumnId;
        $columnKeyModel->keyName = $postData[$columnKey->key_name];
        $columnKeyModel->uniqueName = $uniqueName;
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

    private function getRelationalKeyUniqueName(array $schemaDataList, $keyType, $primaryId, $referenceId) {
        $uniqueName = "";
        /*$relationalKeyType = RelationalKeyType::getByName($keyType);
        if($relationalKeyType == RelationalKeyType::FOREIGN) {
            $uniqueName .= "uk";
        } else if($relationalKeyType == RelationalKeyType::INDEX) {
            $uniqueName .= "in";
        } else if($relationalKeyType == RelationalKeyType::PRIMARY) {
            $uniqueName .= "pk";
        } else if($relationalKeyType == RelationalKeyType::UNIQUE) {
            $uniqueName .= "uk";
        }*/
        $separator = ">";
        $uniqueName = strtolower(mb_substr($keyType, 0, 1)) . "k";
        $primaryColumnInfo = SchemaUniqueNameGenerator::columnNameUniqueText($schemaDataList, $primaryId);
        $referenceColumnInfo = SchemaUniqueNameGenerator::columnNameUniqueText($schemaDataList, $referenceId);
        $uniqueName .= "$separator{$primaryColumnInfo["schema"]}$separator{$primaryColumnInfo["table"]}";
        if(!empty($referenceColumnInfo)) {
            $uniqueName .= "$separator{$primaryColumnInfo["column"]}$separator{$referenceColumnInfo["table"]}$separator{$referenceColumnInfo["column"]}";
        } else {
            $uniqueName .= "$separator{$primaryColumnInfo["column"]}";
        }
        return $uniqueName;
    }
}
?>