<?php
use App\DatabaseSchema\Helper\Database\Data\Retrieve\DbRetrieveDatabaseSchemaData;
use RzSDK\URL\SiteUrl;
use RzSDK\Log\DebugLog;
?>
<?php
global $workingBaseUrl;
global $schemaDataList, $selectedTableId;
$selectedTableId = "";
$fullUrlOnly = SiteUrl::getUrlOnly();
//echo $fullUrlOnly;
?>
<?php
if(!empty($_REQUEST)){
    if(!empty($_REQUEST["search_by_table_id"])) {
        $selectedTableId = $_REQUEST["search_by_table_id"];
    }
}
?>
<?php
$dbTableDataList = array();
if(!empty($selectedTableId)) {
    $dbDatabaseSchemaData = new DbRetrieveDatabaseSchemaData();
    $dbTableDataList = $dbDatabaseSchemaData->getAllTableDataBySchemaId("", $selectedTableId);
    //DebugLog::log($dbTableData);
}
?>
<?php
$htmlOutput = "";
if(!empty($dbTableDataList)) {
    $htmlOutput .= "<br />";
    //DebugLog::log($dbTableData);
    $htmlOutput .= "<table border=\"0\" width=\"100%\" class=\"table-data-search-result\">\n";
    $htmlOutput .= "<tr>\n";
    $htmlOutput .= "<th colspan=\"9\">Table Name</th>\n";
    $htmlOutput .= "<tr>\n";
    foreach($dbTableDataList as $dbTableData) {
        //DebugLog::log($dbTableData);
        if(!empty($dbTableData)) {
            $htmlOutput .= "<tr>\n";
            $htmlOutput .= "<td colspan=\"9\">{$dbTableData->tableName}</td>\n";
            $htmlOutput .= "<tr>\n";
            $htmlOutput .= "<tr>\n";
            $htmlOutput .= "<td colspan=\"9\">&nbsp;</td>\n";
            $htmlOutput .= "<tr>\n";
            //DebugLog::log($dbTableData);
            $htmlOutput .= "<tr>\n";
            $htmlOutput .= "<th>Sl</th>\n";
            $htmlOutput .= "<th>Column Name</th>\n";
            $htmlOutput .= "<th>Data Type</th>\n";
            $htmlOutput .= "<th>Is Null</th>\n";
            $htmlOutput .= "<th>Is Default</th>\n";
            $htmlOutput .= "<th>Default Value</th>\n";
            $htmlOutput .= "<th>Comment</th>\n";
            $htmlOutput .= "<th>Delete</th>\n";
            $htmlOutput .= "<th>Edit</th>\n";
            $htmlOutput .= "<tr>\n";
            foreach($dbTableData->columnDataList as $dbColumnData) {
                $actionUrl = "{$fullUrlOnly}?search_by_table_id=$selectedTableId&action_column_id=$dbColumnData->id&selected_table_name={$dbTableData->tableName}";
                $htmlOutput .= "<tr>\n";
                $htmlOutput .= "<td>{$dbColumnData->columnOrder}</td>\n";
                $htmlOutput .= "<td>{$dbColumnData->columnName}</td>\n";
                $htmlOutput .= "<td>{$dbColumnData->dataType}</td>\n";
                $htmlOutput .= "<td>{$dbColumnData->isNullable}</td>\n";
                $htmlOutput .= "<td>{$dbColumnData->haveDefault}</td>\n";
                $htmlOutput .= "<td>{$dbColumnData->defaultValue}</td>\n";
                $htmlOutput .= "<td>{$dbColumnData->columnComment}</td>\n";
                $htmlOutput .= "<td class=\"action-delete-button\"><a href=\"{$actionUrl}\">Delete</a></td>\n";
                $htmlOutput .= "<td class=\"action-edit-button\"><a href=\"{$actionUrl}\">Edit</a></td>\n";
                $htmlOutput .= "<tr>\n";
            }
        }
    }
    $htmlOutput .= "</table>";
}
?>
<?php
echo $htmlOutput;
?>