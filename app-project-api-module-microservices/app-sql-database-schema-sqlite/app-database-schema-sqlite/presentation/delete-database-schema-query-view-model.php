<?php
namespace App\DatabaseSchema\Presentation\ViewModels;
?>
<?php
use App\DatabaseSchema\Domain\Repositories\DeleteDatabaseSchemaQueryRepositoryInterface;
use App\DatabaseSchema\Helper\Utils\DeleteActionType;
use RzSDK\Log\DebugLog;
?>
<?php
class DeleteDatabaseSchemaQueryViewModel {
    private $repository;

    public function __construct(DeleteDatabaseSchemaQueryRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function onDeleteDatabaseSchemaQuery($postData) {
        //DebugLog::log($postData);
        $deleteActionType = DeleteActionType::NONE;
        $postActionKey = $postData["database_delete_action_key"];
        $deleteActionType = DeleteActionType::getByValue($postActionKey);
        $message = $this->repository->onDeleteDatabaseSchemaTableData($deleteActionType);
        DebugLog::log($message);
    }
}
?>