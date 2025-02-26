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
use App\DatabaseSchema\Helper\Database\Data\Retrieve\DbRetrieveDatabaseSchemaData;
use App\DatabaseSchema\Html\Select\DropDown\HtmlSelectDropDown;
use App\DatabaseSchema\Schema\Build\RawQuery\BuildDatabaseSchemaRawQuery;
use RzSDK\Log\DebugLog;
?>
<?php
?>
<?php
$selectedSchemaId = "";
$schemaId = "";
if(!empty($_REQUEST)) {
    //DebugLog::log($_REQUEST);
    $selectedSchemaId = $_REQUEST["search_by_schema_id"];
    $schemaId = $_REQUEST["search_by_schema_id"];
}
?>
<?php
/*$databaseSchemaRawQuery = new DatabaseSchemaRawQuery();
$databaseSchemaList = $databaseSchemaRawQuery->getDatabaseSchema($schemaId, "", "", function ($item, $depth, $isParent, $isParentClose = false) {
    $indent = str_repeat(" ", ($depth + 1));
    $data = "";

    if($item instanceof DatabaseSchemaModel) {
        $databaseSchema = new DatabaseSchema();
        $tempData = "INSERT INTO tbl_database_schema"
            . "({$databaseSchema->id}, {$databaseSchema->schema_name}, {$databaseSchema->schema_version}, {$databaseSchema->table_prefix}, {$databaseSchema->database_comment}, {$databaseSchema->modified_date}, {$databaseSchema->created_date})"
            . " VALUES"
            . "('{$item->id}', '{$item->schemaName}', '{$item->schemaVersion}', '{$item->tablePrefix}', '{$item->databaseComment}', '{$item->modifiedDate}', '{$item->createdDate}');";
        if($isParent) {
            $data .= $tempData;
        } else {
            if($isParentClose) {
                //$data .= "</ul>\n{$indent}</li>\n";
                $data .= "\n";
            } else {
                $data .= $tempData;
            }
        }
    } else if($item instanceof TableDataModel) {
        $tempTableData = new TableData();
        $data .= "INSERT INTO tbl_table_data"
            . "({$tempTableData->schema_id}, {$tempTableData->id}, {$tempTableData->table_order}, {$tempTableData->table_name}, {$tempTableData->table_comment}, {$tempTableData->column_prefix}, {$tempTableData->modified_date}, {$tempTableData->created_date})"
            . " VALUES"
            . "('{$item->schemaId}', '{$item->id}', '{$item->tableOrder}', '{$item->tableName}', '{$item->tableComment}', '{$item->columnPrefix}', '{$item->modifiedDate}', '{$item->createdDate}');";
    } else if($item instanceof ColumnDataModel) {
        $tempColumnData = new ColumnData();
        $data .= "INSERT INTO tbl_column_data"
            . "({$tempColumnData->table_id}, {$tempColumnData->id}, {$tempColumnData->column_order}, {$tempColumnData->column_name}, {$tempColumnData->data_type}, {$tempColumnData->is_nullable}, {$tempColumnData->have_default}, {$tempColumnData->default_value}, {$tempColumnData->column_comment}, {$tempColumnData->modified_date}, {$tempColumnData->created_date})"
            . " VALUES"
            . "('{$item->tableId}', '{$item->id}', '{$item->columnOrder}', '{$item->columnName}', '{$item->dataType}', '{$item->isNullable}', '{$item->haveDefault}', '{$item->defaultValue}', '{$item->columnComment}', '{$item->modifiedDate}', '{$item->createdDate}');";
    } else if($item instanceof ColumnKeyModel) {
        $tempColumnKey  = new ColumnKey();
        $data .= "INSERT INTO tbl_column_key"
            . "({$tempColumnKey->id}, {$tempColumnKey->working_table}, {$tempColumnKey->main_column}, {$tempColumnKey->key_type}, {$tempColumnKey->reference_column}, {$tempColumnKey->key_name}, {$tempColumnKey->unique_name}, {$tempColumnKey->modified_date}, {$tempColumnKey->created_date})"
            . " VALUES"
            . "('{$item->id}', '{$item->workingTable}', '{$item->mainColumn}', '{$item->keyType}', '{$item->referenceColumn}', '{$item->keyName}', '{$item->uniqueName}', '{$item->uniqueName}', '{$item->modifiedDate}', '{$item->createdDate}');";
    } else if($item instanceof CompositeKeyModel) {
        $tempCompositeKey = new CompositeKey();
        $data .= "INSERT INTO tbl_composite_key"
            . "({$tempCompositeKey->key_id}, {$tempCompositeKey->id}, {$tempCompositeKey->primary_column}, {$tempCompositeKey->composite_column}, {$tempCompositeKey->key_name}, {$tempCompositeKey->modified_date}, {$tempCompositeKey->created_date})"
            . " VALUES"
            . "('{$item->keyId}', '{$item->id}', '{$item->primaryColumn}', '{$item->compositeColumn}', '{$item->keyName}', '{$item->modifiedDate}', '{$item->createdDate}');";
    }
    return $data . "\n";
});
$htmlOutput = "<pre>{$databaseSchemaList["data"]}</pre>";*/
$rawQueryOutput = array(
    "table" => [],
    "data" => "",
);
if(!empty($schemaId)) {
    $rawQueryOutput = array();
    $buildSchemaRawQuery = new BuildDatabaseSchemaRawQuery();
    $rawQueryOutput = $buildSchemaRawQuery->buildDatabaseSchemaRawQuery($schemaId, "", "");
}
?>
<?php
$dbRetrieveSchemaData = (new DbRetrieveDatabaseSchemaData())->getAllDatabaseSchemaDataOnly();
//DebugLog::log($dbRetrieveSchemaData);
?>
<?php
$selectedSchemaId = "";
$schemaId = "";
if(!empty($_REQUEST)) {
    //DebugLog::log($_REQUEST);
    $selectedSchemaId = $_REQUEST["search_by_schema_id"];
    $schemaId = $_REQUEST["search_by_schema_id"];
}
if(!empty($dbRetrieveSchemaData)) {
    $schemaSelectDropDown = HtmlSelectDropDown::schemaSelectDropDown("search_by_schema_id", $dbRetrieveSchemaData, $selectedSchemaId);
}
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
<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="GET">
    <table valign="right" width="100%" class="table-search-by-fields">
        <tr>
            <td>Search By:</td>
            <td width="10px"></td>
            <td><?= $schemaSelectDropDown; ?></td>
            <td width="10px"></td>
            <td class="button-search"><button type="submit">Generate SQL Statement</button></td>
        </tr>
    </table>
</form>
<br />
<table class="database-schema-content">
    <tr>
        <td>
            <pre>
                <?= "\n\n{$rawQueryOutput["data"]}\n\n"; ?>
            </pre>
        </td>
    </tr>
</table>