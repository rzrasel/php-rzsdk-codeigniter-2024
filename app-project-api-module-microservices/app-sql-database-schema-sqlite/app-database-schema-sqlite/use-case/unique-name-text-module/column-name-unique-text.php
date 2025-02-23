<?php
namespace App\DatabaseSchema\Schema\Unique\Name;
?>
<?php
use App\DatabaseSchema\Helper\Data\Finder\DatabaseSchemaDataFinder;
use App\DatabaseSchema\Helper\Recursion\Traverse\RecursiveCallbackSingleModelData;
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use App\DatabaseSchema\Domain\Models\ColumnDataModel;
use RzSDK\Log\DebugLog;
?>
<?php
trait ColumnNameUniqueText {
    public static function columnNameUniqueText($schemaDataList, $primaryColumnId, $referenceColumnId = null) {
        $databaseDataFinder = new DatabaseSchemaDataFinder($schemaDataList);
        $primaryDataList = $databaseDataFinder->getColumnDetails($primaryColumnId);
        return $primaryDataList;
    }
}
?>