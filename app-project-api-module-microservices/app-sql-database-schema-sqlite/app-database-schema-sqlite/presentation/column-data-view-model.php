<?php
namespace App\DatabaseSchema\Presentation\ViewModels;
?>
<?php
use App\DatabaseSchema\Data\Entities\TableData;
use App\DatabaseSchema\Domain\Models\ColumnDataModel;
use App\DatabaseSchema\Domain\Repositories\ColumnDataRepositoryInterface;
?>
<?php
class ColumnDataViewModel {
    private $repository;

    public function __construct(ColumnDataRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function getTable(int $id): ?ColumnDataModel {
        return $this->repository->getById($id);
    }

    public function insertTable(ColumnDataModel $columnData) {
        $this->repository->save($columnData);
    }

    public function createTable(ColumnDataModel $columnData): void {
        $this->repository->create($columnData);
    }
}
?>