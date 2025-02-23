<?php
namespace App\DatabaseSchema\Domain\Repositories;
?>
<?php
use App\DatabaseSchema\Domain\Models\TableDataModel;
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
?>
<?php
interface TableDataRepositoryInterface {
    public function getAllDatabaseSchemaData(): array|bool;
    public function create(TableDataModel $tableData): void;
    //
    public function getById(int $tableDataId): ?TableDataModel;
    public function findBySchemaId(int $tableDataId): ?TableDataModel;
    public function save(TableDataModel $tableData): void;
    public function delete(int $id): void;
}
?>