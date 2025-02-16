<?php
namespace App\DatabaseSchema\Domain\Repositories;
?>
<?php
use App\DatabaseSchema\Domain\Models\TableDataModel;
?>
<?php
interface TableDataRepositoryInterface {
    public function getById(int $tableDataId): ?TableDataModel;
    public function findBySchemaId(int $tableDataId): ?TableDataModel;
    public function create(TableDataModel $tableData): void;
    public function save(TableDataModel $tableData): void;
    public function delete(int $id): void;
}
?>