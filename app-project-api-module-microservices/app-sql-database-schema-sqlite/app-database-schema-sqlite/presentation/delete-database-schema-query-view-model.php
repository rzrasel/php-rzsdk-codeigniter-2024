<?php
namespace App\DatabaseSchema\Presentation\ViewModels;
?>
<?php
use App\DatabaseSchema\Domain\Repositories\DeleteDatabaseSchemaQueryRepositoryInterface;
?>
<?php
class DeleteDatabaseSchemaQueryViewModel {
    private $repository;

    public function __construct(DeleteDatabaseSchemaQueryRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function onDeleteDatabaseSchemaQuery($postData) {}
}
?>