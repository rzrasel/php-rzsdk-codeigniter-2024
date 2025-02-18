<?php
namespace App\DatabaseSchema\Domain\Repositories;
?>
<?php
use App\DatabaseSchema\Domain\Models\ColumnKeyModel;
?>
<?php
interface ColumnKeyRepositoryInterface {
    public function create(ColumnKeyModel $columnKey): void;
    //public function save(ColumnKeyModel $columnKey): void;
    //public function delete(int $id): void;
}
?>