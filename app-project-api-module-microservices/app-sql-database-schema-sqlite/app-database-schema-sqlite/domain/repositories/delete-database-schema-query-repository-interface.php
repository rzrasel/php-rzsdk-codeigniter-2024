<?php
namespace App\DatabaseSchema\Domain\Repositories;
?>
<?php
use App\DatabaseSchema\Helper\Utils\DeleteActionType;
?>
<?php
interface DeleteDatabaseSchemaQueryRepositoryInterface {
    public function onDeleteDatabaseSchemaTableData(DeleteActionType $actionType): string;
}
?>