<?php
use App\DatabaseSchema\Helper\Database\Data\Retrieve\DatabaseSchemaRawQuery;
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use App\DatabaseSchema\Domain\Models\ColumnDataModel;
use App\DatabaseSchema\Domain\Models\ColumnKeyModel;
use App\DatabaseSchema\Domain\Models\CompositeKeyModel;
use App\DatabaseSchema\Data\Entities\DatabaseSchema;
use App\DatabaseSchema\Data\Entities\TableData;
use App\DatabaseSchema\Data\Entities\ColumnData;
use App\DatabaseSchema\Data\Entities\ColumnKey;
use App\DatabaseSchema\Data\Entities\CompositeKey;
use RzSDK\Log\DebugLog;
?>
<?php
/*$prevDepth = 0;
$databaseSchemaRawQuery = new DatabaseSchemaRawQuery();
$htmlOutput = "\n<ul>\n";
$databaseSchemaList = $databaseSchemaRawQuery->getDatabaseSchema("", "", "", function ($item, $depth, $isParent, $isParentClose = false) {
    $indent = str_repeat(" ", ($depth + 1));
    $data = "";

    if ($item instanceof DatabaseSchemaModel) {
        //$data .= "{$indent}<li>{$item->schemaName}";
        if ($isParent) {
            $data .= "{$indent}<li>{$item->schemaName}\n{$indent}<ul>\n";
        } else {
            if($isParentClose) {
                $data .= "</ul>\n{$indent}</li>\n";
            } else {
                $data .= "{$indent}<li>{$item->schemaName}</li>\n";
            }
        }
    } else if ($item instanceof TableDataModel) {
        $data .= "{$indent}<li>{$item->tableName}</li>\n";
    }
    return $data;
});
$htmlOutput .= $databaseSchemaList["data"];
$htmlOutput .= "</ul>\n";

echo $htmlOutput;
DebugLog::log($databaseSchemaList);*/
$databaseSchemaRawQuery = new DatabaseSchemaRawQuery();
$databaseSchemaList = $databaseSchemaRawQuery->getDatabaseSchema("", "", "", function ($item, $depth, $isParent, $isParentClose = false) {
    $indent = str_repeat(" ", ($depth + 1));
    $data = "";

    if ($item instanceof DatabaseSchemaModel) {
        $databaseSchema = new DatabaseSchema();
        $tempData = "INSERT INTO tbl_database_schema"
            . "({$databaseSchema->id}, {$databaseSchema->schema_name}, {$databaseSchema->schema_version}, {$databaseSchema->table_prefix}, {$databaseSchema->database_comment}, {$databaseSchema->modified_date}, {$databaseSchema->created_date})"
            . " VALUES"
            . "('{$item->id}', '{$item->schemaName}', '{$item->schemaVersion}', '{$item->tablePrefix}', '{$item->databaseComment}', '{$item->modifiedDate}', '{$item->createdDate}');";
        if ($isParent) {
            $data .= $tempData;
        } else {
            if($isParentClose) {
                //$data .= "</ul>\n{$indent}</li>\n";
            } else {
                $data .= $tempData;
            }
        }
    } else if ($item instanceof TableDataModel) {
        $tempTableData = new TableData();
        $data .= "INSERT INTO tbl_table_data"
            . "({$tempTableData->schema_id}, {$tempTableData->id}, {$tempTableData->table_order}, {$tempTableData->table_name}, {$tempTableData->table_comment}, {$tempTableData->column_prefix}, {$tempTableData->modified_date}, {$tempTableData->created_date})"
            . " VALUES"
            . "('{$item->schemaId}', '{$item->id}', '{$item->tableOrder}', '{$item->tableName}', '{$item->tableComment}', '{$item->columnPrefix}', '{$item->modifiedDate}', '{$item->createdDate}');";
    } else if ($item instanceof ColumnDataModel) {
        $data .= "INSERT INTO tbl_column_data"
            . "()"
            . " VALUES"
            . "();";
    } else if ($item instanceof ColumnKeyModel) {
        $data .= "INSERT INTO tbl_column_key"
            . "()"
            . " VALUES"
            . "();";
    } else if ($item instanceof CompositeKeyModel) {
        $data .= "INSERT INTO tbl_composite_key"
            . "()"
            . " VALUES"
            . "();";
    }
    return $data . "\n";
});
$htmlOutput = "<pre>{$databaseSchemaList["data"]}</pre>";
?>
<table class="form-heading">
    <tr>
        <td></td>
    </tr>
    <tr>
        <td>Database Schema Raw Query Statement</td>
    </tr>
    <tr>
        <td></td>
    </tr>
</table>
<br />
<table class="database-schema-content">
    <tr>
        <td>
            <pre>
                <?= $htmlOutput; ?>
            </pre>
        </td>
    </tr>
</table>