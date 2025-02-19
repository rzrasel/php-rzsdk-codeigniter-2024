<?php
namespace App\DatabaseSchema\Domain\Models;
?>
<?php
class DatabaseSchemaModel {
    public $id;
    //public $uniqueName;
    public $schemaName;
    public $schemaVersion;
    public $tablePrefix;
    public $databaseComment;
    public $modifiedDate;
    public $createdDate;
    public array $tableDataList = []; // One-to-many relationship with TableData
    public function __construct(
        int $id = 0,
        //string $uniqueName = "",
        string $schemaName = "",
        ?string $schemaVersion = "",
        ?string $tablePrefix = "",
        ?string $databaseComment = "",
        string $modifiedDate = "",
        string $createdDate = "",
        ?array $tableDataList = []
    ) {
        $this->id = $id;
        //$this->uniqueName = $uniqueName;
        $this->schemaName = $schemaName;
        $this->schemaVersion = $schemaVersion;
        $this->tablePrefix = $tablePrefix;
        $this->databaseComment = $databaseComment;
        $this->modifiedDate = $modifiedDate ?? date('Y-m-d H:i:s');
        $this->createdDate = $createdDate ?? date('Y-m-d H:i:s');
        $this->tableDataList = $tableDataList;
    }

    public function getVarList() {
        $result = array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );
        return array_keys($result);
    }

    public function getVarListWithKey() {
        return array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );
    }

    public static function getVarByValue(string $value, array $array): string|null {
        return array_search($value, $array, true) ?: null;
    }

    public function traverse(callable $callback): string {
        $result = $callback($this); // Apply callback to DatabaseSchemaModel

        // Process nested table data
        foreach ($this->tableDataList as $tableData) {
            $result .= $tableData->traverse($callback);
        }

        return $result;
    }
}
?>
<?php
/*// ---- Output as Text List ----
$listOutput = "";
foreach ($schemas as $schema) {
    $listOutput .= $schema->traverse(function ($item) {
        if ($item instanceof DatabaseSchemaModel) {
            return "- {$item->schemaName}\n";
        } elseif ($item instanceof TableDataModel) {
            return "  - Table: {$item->tableName}\n";
        }
        return "";
    });
}
echo "Text List Output:\n$listOutput\n";*/

/*// ---- Output as HTML List ----
$htmlOutput = "<ul>";
foreach ($schemas as $schema) {
    $htmlOutput .= $schema->traverse(function ($item) {
        if ($item instanceof DatabaseSchemaModel) {
            return "<li>{$item->schemaName}</li>";
        } elseif ($item instanceof TableDataModel) {
            return "<ul><li>{$item->tableName}</li></ul>";
        }
        return "";
    });
}
$htmlOutput .= "</ul>";

echo "\nHTML Output:\n$htmlOutput";*/
?>
