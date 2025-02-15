<?php
namespace App\DatabaseSchema\Presentation\ViewModels;
?>
<?php
use App\DatabaseSchema\Data\Entities\DatabaseSchema;
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use App\DatabaseSchema\Domain\Repositories\DatabaseSchemaRepositoryInterface;
?>
<?php
class DatabaseSchemaViewModel {
    private $repository;

    public function __construct(DatabaseSchemaRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function getSchema(int $id): ?DatabaseSchemaModel {
        return $this->repository->getById($id);
    }

    public function insertSchema(DatabaseSchemaModel $schema) {
        $this->repository->save($schema);
    }

    public function createSchema(DatabaseSchemaModel $schema): void {
        $this->repository->create($schema);
    }
}
?>