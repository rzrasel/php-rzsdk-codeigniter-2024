<?php
namespace App\DatabaseSchema\Presentation\ViewModels;
?>
<?php
use App\DatabaseSchema\Data\Entities\TableData;
use App\DatabaseSchema\Domain\Models\ColumnKeyModel;
use App\DatabaseSchema\Domain\Repositories\ColumnKeyRepositoryInterface;
?>
<?php
class ColumnKeyViewModel {
    private $repository;

    public function __construct(ColumnKeyRepositoryInterface $repository) {
        $this->repository = $repository;
    }

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