<?php
require_once("include.php");
?>
<?php
use App\Utils\Menu\Builder\BuildHtmlMenu;
use App\Utils\Menu\Builder\HtmlMenuDataList;
use App\DatabaseSchema\Data\Entities\TableData;
use App\DatabaseSchema\Data\Entities\ColumnData;
use App\DatabaseSchema\Data\Entities\ColumnKey;
use RzSDK\Log\DebugLog;
?>
<?php
global $workingBaseUrl;
?>
<?php
$dataList = HtmlMenuDataList::sqlDatabaseDataList();
?>
<?php
$buildHtmlMenu = new BuildHtmlMenu();
$sideBarMenu = $buildHtmlMenu->buildTopbarMenu($dataList, "{$workingBaseUrl}/");
?>
<?php
function extractTableAndColumns($sql) {
    // Initialize arrays to hold TableData and ColumnData objects
    $tableData = [];
    $columnData = [];

    // Regex to extract CREATE TABLE statements
    preg_match_all("/CREATE\s+TABLE\s+IF\s+NOT\s+EXISTS\s+(\S+)\s*\((.*?)\);/is", $sql, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
        // Table Data
        $tableName = $match[1];  // Table name (e.g., tbl_publisher_data)
        $columnsSql = $match[2]; // Column definitions

        // Initialize the TableData object
        $table = new TableData();
        $table->table_name = $tableName;

        // Store the TableData object
        $tableData[] = $table;

        // Extract Column Data
        preg_match_all("/\s*(\w+)\s+(\w+)\s*(NULL|NOT\s+NULL)?\s*(DEFAULT\s+.*|CHECK\s*\(.*\))?[\s,]*/i", $columnsSql, $columnMatches, PREG_SET_ORDER);

        $columnOrder = 1; // Initialize column order
        foreach ($columnMatches as $columnMatch) {
            $column = new ColumnData();
            $column->column_name = $columnMatch[1];
            $column->data_type = $columnMatch[2];

            // Safe check for nullable and default values
            $column->is_nullable = isset($columnMatch[3]) && strtolower($columnMatch[3]) == 'null' ? 'YES' : 'NO';
            $column->have_default = isset($columnMatch[4]) && strpos($columnMatch[4], 'DEFAULT') !== false ? 'YES' : 'NO';
            $column->default_value = null;
            if (isset($columnMatch[4]) && strpos($columnMatch[4], 'DEFAULT') !== false) {
                preg_match("/DEFAULT\s+(.*)/", $columnMatch[4], $defaultMatch);
                if (isset($defaultMatch[1])) {
                    $column->default_value = trim($defaultMatch[1]);
                }
            }
            $column->column_order = $columnOrder++;

            // Add the column to the columnData array
            $columnData[] = $column;
        }
    }

    return [$tableData, $columnData];
}
function extractTableAndColumnsFromSql(string $sql): array {
    $tableData = new TableData();
    $columnsData = [];

    // Extract table name
    preg_match('/CREATE TABLE IF NOT EXISTS\s+(\w+)\s*\(/', $sql, $tableMatches);
    if (!empty($tableMatches[1])) {
        $tableData->table_name = $tableMatches[1];
    }

    // Extract column definitions
    preg_match('/\((.*)\);/s', $sql, $columnMatches);
    if (!empty($columnMatches[1])) {
        $columnDefinitions = explode(",", trim($columnMatches[1]));

        foreach ($columnDefinitions as $index => $columnDefinition) {
            $columnDefinition = trim($columnDefinition);

            // Skip if it's a constraint (e.g., PRIMARY KEY, FOREIGN KEY, CHECK, etc.)
            if (preg_match('/^(PRIMARY KEY|FOREIGN KEY|CHECK|UNIQUE)/i', $columnDefinition)) {
                continue;
            }

            // Extract column name, data type, and constraints
            preg_match('/(\w+)\s+([\w\(\)]+)\s*(.*)/', $columnDefinition, $columnMatches);
            if (!empty($columnMatches)) {
                $columnData = new ColumnData();
                $columnData->column_name = $columnMatches[1];
                $columnData->data_type = $columnMatches[2];
                $columnData->column_order = $index + 1;

                // Handle constraints
                $constraints = strtoupper($columnMatches[3] ?? '');
                $columnData->is_nullable = (strpos($constraints, 'NOT NULL') === false) ? "true" : "false";
                $columnData->have_default = (strpos($constraints, 'DEFAULT') !== false) ? "true" : "false";

                // Extract default value if present
                if ($columnData->have_default === "true") {
                    preg_match('/DEFAULT\s+([^\s]+)/', $constraints, $defaultMatches);
                    $columnData->default_value = $defaultMatches[1] ?? null;
                }

                $columnsData[] = $columnData;
            }
        }
    }

    return [
        'tableData' => $tableData,
        'columnsData' => $columnsData,
    ];
}
function extractTableColumnsAndKeysFromSqlV1(string $sql): array {
    $tableData = new TableData();
    $columnsData = [];
    $columnKeys = [];

    // Extract table name
    preg_match('/CREATE TABLE IF NOT EXISTS\s+(\w+)\s*\(/', $sql, $tableMatches);
    if (!empty($tableMatches[1])) {
        $tableData->table_name = $tableMatches[1];
    }

    // Extract column definitions and constraints
    preg_match('/\((.*)\);/s', $sql, $columnMatches);
    if (!empty($columnMatches[1])) {
        $columnDefinitions = explode(",", trim($columnMatches[1]));

        foreach ($columnDefinitions as $index => $columnDefinition) {
            $columnDefinition = trim($columnDefinition);

            // Handle column definitions
            if (preg_match('/^(\w+)\s+([\w\(\)]+)\s*(.*)/', $columnDefinition, $columnMatches)) {
                $columnData = new ColumnData();
                $columnData->column_name = $columnMatches[1];
                $columnData->data_type = $columnMatches[2];
                $columnData->column_order = $index + 1;

                // Handle constraints
                $constraints = strtoupper($columnMatches[3] ?? '');
                $columnData->is_nullable = (strpos($constraints, 'NOT NULL') === false) ? "true" : "false";
                $columnData->have_default = (strpos($constraints, 'DEFAULT') !== false) ? "true" : "false";

                // Extract default value if present
                if ($columnData->have_default === "true") {
                    preg_match('/DEFAULT\s+([^\s]+)/', $constraints, $defaultMatches);
                    $columnData->default_value = $defaultMatches[1] ?? null;
                }

                $columnsData[] = $columnData;
            }

            // Handle constraints (PRIMARY KEY, FOREIGN KEY)
            if (preg_match('/CONSTRAINT\s+(\w+)\s+(PRIMARY KEY|FOREIGN KEY)\s*\(([^)]+)\)/', $columnDefinition, $constraintMatches)) {
                $columnKey = new ColumnKey();
                $columnKey->key_name = $constraintMatches[1];
                $columnKey->key_type = strtolower($constraintMatches[2]);

                // Extract main column(s)
                $mainColumns = explode(",", $constraintMatches[3]);
                $columnKey->main_column = trim($mainColumns[0]);

                // Handle FOREIGN KEY specifics
                if ($columnKey->key_type === "foreign key") {
                    preg_match('/REFERENCES\s+(\w+)\s*\(([^)]+)\)/', $columnDefinition, $referenceMatches);
                    if (!empty($referenceMatches)) {
                        $columnKey->reference_column = trim($referenceMatches[2]);
                        $columnKey->working_table = $tableData->table_name;
                    }
                }

                $columnKeys[] = $columnKey;
            }
        }
    }

    return [
        'tableData' => $tableData,
        'columnsData' => $columnsData,
        'columnKeys' => $columnKeys,
    ];
}
?>
<?php
function extractTableColumnsAndKeysFromSql(string $sql): array {
    $tableData = new TableData();
    $columnsData = [];
    $columnKeys = [];

    // Extract table name
    preg_match('/CREATE TABLE IF NOT EXISTS\s+(\w+)\s*\(/', $sql, $tableMatches);
    if (!empty($tableMatches[1])) {
        $tableData->table_name = $tableMatches[1];
    }

    // Extract column definitions and constraints
    preg_match('/\((.*)\);/s', $sql, $columnMatches);
    if (!empty($columnMatches[1])) {
        $columnDefinitions = explode(",", trim($columnMatches[1]));

        foreach ($columnDefinitions as $index => $columnDefinition) {
            $columnDefinition = trim($columnDefinition);

            // Handle column definitions
            if (preg_match('/^(\w+)\s+([\w\(\)]+)\s*(.*)/', $columnDefinition, $columnMatches)) {
                $columnData = new ColumnData();
                $columnData->column_name = $columnMatches[1];
                $columnData->data_type = $columnMatches[2];
                $columnData->column_order = $index + 1;

                // Handle constraints
                $constraints = strtoupper($columnMatches[3] ?? '');
                $columnData->is_nullable = (strpos($constraints, 'NOT NULL') === false) ? "true" : "false";
                $columnData->have_default = (strpos($constraints, 'DEFAULT') !== false) ? "true" : "false";

                // Extract default value if present
                if ($columnData->have_default === "true") {
                    preg_match('/DEFAULT\s+([^\s]+)/', $constraints, $defaultMatches);
                    $columnData->default_value = $defaultMatches[1] ?? null;
                }

                $columnsData[] = $columnData;
            }

            // Handle PRIMARY KEY constraints
            if (preg_match('/CONSTRAINT\s+(\w+)\s+PRIMARY KEY\s*\(([^)]+)\)/', $columnDefinition, $pkMatches)) {
                $columnKey = new ColumnKey();
                $columnKey->key_name = $pkMatches[1];
                $columnKey->key_type = "primary key";
                $columnKey->working_table = $tableData->table_name;
                $columnKey->main_column = trim($pkMatches[2]);
                $columnKey->reference_column = []; // PRIMARY KEY has no reference column
                $columnKeys[] = $columnKey;
            }

            // Handle FOREIGN KEY constraints
            if (preg_match('/CONSTRAINT\s+(\w+)\s+FOREIGN KEY\s*\(([^)]+)\)\s+REFERENCES\s+(\w+)\s*\(([^)]+)\)/', $columnDefinition, $fkMatches)) {
                $columnKey = new ColumnKey();
                $columnKey->key_name = $fkMatches[1];
                $columnKey->key_type = "foreign key";
                $columnKey->working_table = $tableData->table_name;
                $columnKey->main_column = trim($fkMatches[2]);
                $columnKey->reference_column = [trim($fkMatches[3]), trim($fkMatches[4])]; // [referenced_table, referenced_column]
                $columnKeys[] = $columnKey;
            }
        }
    }

    return [
        'tableData' => $tableData,
        'columnsData' => $columnsData,
        'columnKeys' => $columnKeys,
    ];
}
?>
<?php
$sql = "CREATE TABLE IF NOT EXISTS tbl_publisher_data (
    id                    BIGINT(20)     NOT NULL,
    name                  TEXT           NOT NULL,
    country               TEXT           NULL,
    established_year      DATE           NULL,
    CONSTRAINT pk_publisher_data_id PRIMARY KEY(id)
);";

$sql = "CREATE TABLE IF NOT EXISTS tbl_book_content (
    section_id            BIGINT(20)     NOT NULL,
    id                    BIGINT(20)     NOT NULL,
    title                 TEXT           NULL,
    content_details       TEXT           NULL,
    order_index           INT(3)         NOT NULL,
    content_type          TEXT           NOT NULL CHECK(content_type IN ('text', 'image', 'video', 'link', 'reference')),
    reference_book_id     BIGINT(20)     NULL,
    CONSTRAINT pk_book_content_id PRIMARY KEY(id),
    CONSTRAINT fk_book_content_reference_book_id_book_data_id FOREIGN KEY(reference_book_id) REFERENCES tbl_book_data(id),
    CONSTRAINT fk_book_content_section_id_book_sectioning_id FOREIGN KEY(section_id) REFERENCES tbl_book_sectioning(id)
);";
/*$dataList = extractTableColumnsAndKeysFromSql($sql);
//$dataList = extractTableAndColumns($sql);
//list($tableData, $columnData) = $dataList;
DebugLog::log($dataList);*/
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sql Database Schema SQLite</title>
    <link rel="stylesheet" type="text/css" href="<?= $workingBaseUrl; ?>/css/style.css">
</head>
<body>
<table class="main-body-container">
    <tr>
        <td class="main-left-sidebar-container"><?= $sideBarMenu; ?></td>
        <td class="main-body-content-container">
            <?php
            require_once("view-parts/extract-table-column-data-part.php");
            ?>
        </td>
    </tr>
</table>
</body>
</html>