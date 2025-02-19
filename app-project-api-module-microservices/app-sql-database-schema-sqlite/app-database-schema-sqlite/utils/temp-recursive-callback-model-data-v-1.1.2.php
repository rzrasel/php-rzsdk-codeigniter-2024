<?php
class RecursiveCallbackSingleModelData {
    public function onRecursionTraverse($recursiveData, callable $callback, int $level = 0): string {
        $result = "";

        // Handle arrays
        if (is_array($recursiveData) && !empty($recursiveData)) {
            foreach ($recursiveData as $item) {
                // Apply callback to the current item
                $result .= $callback($item, $level);

                // Recursively process nested data
                if (is_array($item) || is_object($item)) {
                    $result .= $this->onRecursionTraverse($item, $callback, $level + 1);
                }
            }
        }
        // Handle single items (non-array)
        elseif (!empty($recursiveData)) {
            $result .= $callback($recursiveData, $level);
        }

        return $result;
    }
}
?>
<?php
// Example Models
class DatabaseSchemaModel {
    public string $name;
    public array $tableDataList;

    public function __construct(string $name, array $tableDataList = []) {
        $this->name = $name;
        $this->tableDataList = $tableDataList;
    }
}

class TableDataModel {
    public string $name;
    public array $columnList;

    public function __construct(string $name, array $columnList = []) {
        $this->name = $name;
        $this->columnList = $columnList;
    }
}

class ColumnKeyModel {
    public string $name;
    public ?KeyDataModel $keyDataModel;
    public ?array $dataList;

    public function __construct(string $name, $keyDataModel, array $dataList = []) {
        $this->name = $name;
        $this->keyDataModel = $keyDataModel;
        $this->dataList = $dataList;
    }
}

class KeyDataModel {
    public string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }
}
?>
<?php
// Sample Data
$schemas = [
    new DatabaseSchemaModel("Schema_1", [
        new TableDataModel("Users", [
            new ColumnKeyModel("id", new KeyDataModel("Primary Key")),
            new ColumnKeyModel("name", new KeyDataModel("String")),
            new ColumnKeyModel("email", new KeyDataModel("String"))
        ]),
        new TableDataModel("Orders", [
            new ColumnKeyModel("id", new KeyDataModel("Primary Key"), ["name" => "Rashed", "type" => "success", "message" => "message data", "data" => ["amount" => 1500, "date" => "2025"]]),
            new ColumnKeyModel("user_id", new KeyDataModel("Foreign Key")),
            new ColumnKeyModel("total", new KeyDataModel("Decimal"))
        ])
    ]),
    new DatabaseSchemaModel("Schema_2", [
        new TableDataModel("Products", [
            new ColumnKeyModel("id", new KeyDataModel("Primary Key")),
            new ColumnKeyModel("name", new KeyDataModel("String")),
            new ColumnKeyModel("price", new KeyDataModel("Decimal"))
        ]),
        new TableDataModel("Categories", [
            new ColumnKeyModel("id", null, ["name" => "Rashed - Uz - Zaman", "type" => "success", "message" => "message data", "data" => ["amount" => 1000, "date" => "2025"]]),
            new ColumnKeyModel("category_name", new KeyDataModel("String"), ["name" => "Rz Rasel", "type" => "error", "message" => "message data", "data" => ["amount" => 100, "date" => "2024"]])
        ])
    ])
];
?>
<?php
// Recursive Traversal Object
$recursiveCallbackData = new RecursiveCallbackSingleModelData();

// ---- Output as Text List ----
$listOutput = $recursiveCallbackData->onRecursionTraverse($schemas, function ($item, $indent) {
    if ($item instanceof DatabaseSchemaModel) {
        return "{$indent}- Schema: {$item->name}\n";
    } elseif ($item instanceof TableDataModel) {
        return "{$indent}  - Table: {$item->name}\n";
    } elseif ($item instanceof ColumnKeyModel) {
        return "{$indent}    - Column: {$item->name}\n";
    } elseif ($item instanceof KeyDataModel) {
        return "{$indent}      - Key Type: {$item->name}\n";
    } elseif (is_array($item)) {
        return "{$indent}        - Data: " . json_encode($item) . "\n";
    }
    return "";
});
echo "Text List Output:\n$listOutput\n";
?>
<?php
// ---- Output as HTML List ----
$level = 0;
$htmlOutput = "";
$htmlOutput .= $recursiveCallbackData->onRecursionTraverse($schemas, function ($item, $level) {
    if ($item instanceof DatabaseSchemaModel) {
        return "<li><strong>{$item->name}</strong><ul>";
    } elseif ($item instanceof TableDataModel) {
        return "<li>{$item->name}<ul>";
    } elseif ($item instanceof ColumnKeyModel) {
        return "<li>{$item->name}<ul>";
    } elseif ($item instanceof KeyDataModel) {
        return "<li>Key Type: {$item->name}</li>";
    } elseif (is_array($item)) {
        return "<li>Data: " . json_encode($item) . "<ul>";
    }
    return "";
});
//$htmlOutput .= str_repeat("  </ul></li>\n", $level);
if(!empty($htmlOutput)) {
    // Close the HTML tags properly
    $htmlOutput = "<ul>$htmlOutput</ul></li></ul>";
}

echo "\nHTML Output:\n$htmlOutput";
?>