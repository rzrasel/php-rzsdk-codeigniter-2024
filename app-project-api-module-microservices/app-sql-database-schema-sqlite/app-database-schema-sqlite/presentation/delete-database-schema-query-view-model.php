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

    public function onRunRawQuery($postData) {
        //DebugLog::log($postData);
        if(!array_key_exists("post_task_type", $postData)) {
            return;
        }
        //DebugLog::log($postData);
        $sqlStatement = $postData["sql_statement"];
        // Remove SQL comments and extra text
        // Remove single-line comments
        $input = preg_replace("/--.*\n/", "", $sqlStatement);
        // Remove labels/text
        $sql = preg_replace('/^[-\w\s:]+$/m', '', $input);
        // Step 1: Remove extra new lines and spaces
        $sql = preg_replace("/\s+/", " ", $sql);
        // Remove leading/trailing whitespace
        $sql = trim($sql);
        // Step 2: Split SQL statements properly
        //$statements = array_filter(array_map("trim", explode(";", $sql)));
        // Split SQL statements into an array
        $statements = array_filter(array_map("trim", preg_split("/;\s*/", $sql)));
        // Step 3: Ensure each statement ends with a semicolon
        //$queries = array_map(fn($stmt) => $stmt . ";", $statements);
        $sqlQueries = array_map(function ($stmt) {
            return $stmt . ";"; // Reattach semicolon
        }, $statements);
        //DebugLog::log($sqlQueries);
        $message = $this->repository->onRunRawQuerySchemaTableData($sqlQueries);
        DebugLog::log($message);
    }

    public function onDeleteDatabaseSchemaQuery($postData) {
        if(!array_key_exists("database_delete_action_key", $postData)) {
            return;
        }
        //DebugLog::log($postData);
        $deleteActionType = DeleteActionType::NONE;
        $postActionKey = $postData["database_delete_action_key"];
        $deleteActionType = DeleteActionType::getByValue($postActionKey);
        if($deleteActionType != DeleteActionType::NONE) {
            $message = $this->repository->onDeleteDatabaseSchemaTableData($deleteActionType);
            DebugLog::log($message);
        }
    }
}
?>