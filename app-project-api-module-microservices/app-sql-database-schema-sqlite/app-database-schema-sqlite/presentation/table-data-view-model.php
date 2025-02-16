<?php
namespace App\DatabaseSchema\Presentation\ViewModels;
?>
<?php
use App\DatabaseSchema\Data\Entities\TableData;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use App\DatabaseSchema\Domain\Repositories\TableDataRepositoryInterface;
?>
<?php
class TableDataViewModel {
    private $repository;

    public function __construct(TableDataRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function getSchema(int $id): ?TableDataModel {
        return $this->repository->getById($id);
    }

    public function insertSchema(TableDataModel $tableData) {
        $this->repository->save($tableData);
    }

    public function createSchema(DatabaseSchemaModel $schema): void {
        $this->repository->create($schema);
    }
}
?>