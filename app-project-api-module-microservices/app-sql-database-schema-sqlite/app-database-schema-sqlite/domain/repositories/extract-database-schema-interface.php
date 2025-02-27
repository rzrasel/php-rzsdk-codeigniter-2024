<?php
namespace App\DatabaseSchema\Domain\Repositories;
?>
<?php
use App\DatabaseSchema\Domain\Models\TableDataModel;
use App\DatabaseSchema\Domain\Models\ColumnDataModel;
use App\DatabaseSchema\Domain\Models\ColumnKeyModel;
?>
<?php
interface ExtractDatabaseSchemaInterface {
    public function getAllDatabaseSchemaData(): array|bool;
    public function onInsertTableData(TableDataModel $tableDataModel): TableDataModel;
    public function onInsertColumnData(ColumnDataModel $columnDataModel): ColumnDataModel;
    public function onInsertColumnKey($schemaId, ColumnKeyModel $columnKeyModel): ColumnKeyModel;
}
?>
