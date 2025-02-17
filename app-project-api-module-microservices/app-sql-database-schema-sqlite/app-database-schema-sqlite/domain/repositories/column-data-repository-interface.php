<?php
namespace App\DatabaseSchema\Domain\Repositories;
?>
<?php
use App\DatabaseSchema\Domain\Models\ColumnDataModel;
?>
<?php
interface ColumnDataRepositoryInterface {
    public function create(ColumnDataModel $columnData): void;
    public function save(ColumnDataModel $columnData): void;
    public function delete(int $id): void;
}
?>