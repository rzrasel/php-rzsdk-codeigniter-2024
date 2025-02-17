<?php
namespace App\DatabaseSchema\Presentation\ViewModels;
?>
<?php
use App\DatabaseSchema\Data\Entities\TableData;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use App\DatabaseSchema\Domain\Repositories\TableDataRepositoryInterface;
?>
<?php
class CompositeKeyViewModel {
    private $repository;

    public function __construct(TableDataRepositoryInterface $repository) {
        $this->repository = $repository;
    }

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