<?php
require_once("include.php");
?>
<?php
use RzSDK\Database\SqliteConnection;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Utils\String\StringHelper;
use RzSDK\Identification\UniqueIntId;
use RzSDK\Database\Schema\DbTableSchema;
use RzSDK\Database\Schema\TblParentChildInfo;
?>
<?php
//
$uniqueIntId = new UniqueIntId();
$uniqueId = $uniqueIntId->getId();
echo "<br />";
echo $uniqueId;
echo "<br />";
//
$parentChildInfoTableName = DbTableSchema::parentChildInfoWithPrefix();
$sqlQuery = "SELECT * FROM {$parentChildInfoTableName}";
$sqlQuery = StringHelper::toSingleSpace($sqlQuery);
echo "<br />";
echo $sqlQuery;
echo "<br />";
//
//
$tempTblParentChildInfo = new TblParentChildInfo();
//
//
$dbConn = getDbConnection();
$dbResult = doRunDatabaseQuery($dbConn, $sqlQuery);
foreach($dbResult as $row) {
    echo $row[$tempTblParentChildInfo->name];
    echo "<br />";
}
?>
<?php
echo "<br />";
echo "<br />";
echo "<br />";
$parentTree = getRecursiveParent();
// Display the hierarchical structure
displayRecursiveParent($parentTree);
?>
<?php
// Recursive function to fetch recursive parent
function getRecursiveParent($parentId = null) {
    $parentChildInfoTableName = DbTableSchema::parentChildInfoWithPrefix();
    $sqlQuery = $parentId === null 
        ? "SELECT * FROM {$parentChildInfoTableName} WHERE parent_id IS NULL" 
        : "SELECT * FROM {$parentChildInfoTableName} WHERE parent_id = $parentId";
    $dbConn = getDbConnection();
    $dbResult = doRunDatabaseQuery($dbConn, $sqlQuery);
    //
    $parents = [];
    foreach($dbResult as $row) {
        $row["children"] = getRecursiveParent($row["id"]);
        $parents[] = $row;
    }
    return $parents;
}
?>
<?php
// Function to display the tree
function displayRecursiveParent($parents, $level = 0) {
    /* if(empty($parents)) {
        return;
    } */
    foreach($parents as $category) {
        echo str_repeat("--", $level) . " " . $category["name"] . "<br>";
        if (!empty($category["children"])) {
            displayRecursiveParent($category["children"], $level + 1);
        }
    }
}
function displayProcessData($array, $callback, $level = 0) {
    foreach ($array as $item) {
        $callback($item, $level);
        if (!empty($item["children"])) {
            displayProcessData($item["children"], $callback, $level + 1);
        }
    }
}
displayProcessData($data, function($item, $level) {
    echo str_repeat("--", $level) . " " . $item["name"] . "<br>";
});
?>
<?php
function doRunDatabaseQuery($dbConn, $sqlQuery) {
    return $dbConn->query($sqlQuery);
}
function getDbConnection() {
    $dbFullPath = "../" . DB_PATH . "/" . DB_FILE;
    return new SqliteConnection($dbFullPath);
}
?>