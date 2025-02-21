<?php
require_once("include.php");
?>
<?php
use App\Utils\Menu\Builder\BuildHtmlMenu;
use App\Utils\Menu\Builder\HtmlMenuDataList;
?>
<?php
$baseUrl = BASE_URL . "/app-sql-database-schema-sqlite/";
//echo $baseUrl;
?>
<?php
$dataList = array(
    "database-schema-entry.php" => "Database Schema Entry",
    "table-data-entry.php" => "Table Data Entry",
    "column-data-entry.php" => "Column Data Entry",
    "column-key-entry.php" => "Column Key Entry",
    "composite-key-entry.php" => "Composite Key Entry",
    "database-schema-statement-query.php" => "Database Schema Statement Query",
);
?>
<?php
$buildHtmlMenu = new BuildHtmlMenu();
echo $buildHtmlMenu->buildTopbarMenu($dataList, $baseUrl);
?>
