<?php

namespace App\DatabaseSchema\Domain\Models;

class DatabaseSchemaModel
{
    public string $id;
    public string $schemaName;
    public string $schemaVersion;
    public string $tablePrefix;
    public string $databaseComment;
    public string $modifiedDate;
    public string $createdDate;
    /** @var TableDataModel[] */
    public array $tableDataList = [];

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->schemaName = $data['schemaName'];
        $this->schemaVersion = $data['schemaVersion'];
        $this->tablePrefix = $data['tablePrefix'] ?? '';
        $this->databaseComment = $data['databaseComment'] ?? '';
        $this->modifiedDate = $data['modifiedDate'];
        $this->createdDate = $data['createdDate'];

        // Initialize TableDataModel objects
        if (isset($data['tableDataList']) && is_array($data['tableDataList'])) {
            foreach ($data['tableDataList'] as $tableData) {
                $this->tableDataList[] = new TableDataModel($tableData);
            }
        }
    }
}

class TableDataModel
{
    public string $id;
    public string $tableName;
    public string $schemaId;
    public string $createdDate;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->tableName = $data['tableName'];
        $this->schemaId = $data['schemaId'];
        $this->createdDate = $data['createdDate'];
    }
}

class RecursiveCallbackModelData
{
    public function traverseDatabaseSchema(array $databaseSchemas, callable $callback): string
    {
        $result = "";
        foreach ($databaseSchemas as $schema) {
            $result .= $callback($schema); // Apply callback to DatabaseSchemaModel

            // Process nested table data
            foreach ($schema->tableDataList as $tableData) {
                $result .= $this->traverseTableData($tableData, $callback);
            }
        }
        return $result;
    }

    public function traverseTableData(TableDataModel $tableDataModel, callable $callback): string
    {
        return $callback($tableDataModel);
    }
}

// Sample Database Schema Data
$schemas = [
    new DatabaseSchemaModel([
        'id' => '173989248140720721',
        'schemaName' => 'database-schema-database',
        'schemaVersion' => '1.1.1',
        'modifiedDate' => '2025-02-18 16:28:01',
        'createdDate' => '2025-02-18 16:28:01',
        'tableDataList' => [
            ['id' => 'table_1', 'tableName' => 'users', 'schemaId' => '173989248140720721', 'createdDate' => '2025-02-18'],
            ['id' => 'table_2', 'tableName' => 'orders', 'schemaId' => '173989248140720721', 'createdDate' => '2025-02-18']
        ]
    ]),
    new DatabaseSchemaModel([
        'id' => '173989258101443058',
        'schemaName' => 'database-schema-database-test',
        'schemaVersion' => '1.1.1',
        'modifiedDate' => '2025-02-18 16:29:41',
        'createdDate' => '2025-02-18 16:29:41',
        'tableDataList' => [
            ['id' => 'table_3', 'tableName' => 'products', 'schemaId' => '173989258101443058', 'createdDate' => '2025-02-18']
        ]
    ])
];

// Recursive Traversal Object
$recursiveCallbackData = new RecursiveCallbackModelData();

// ---- Output as Text List ----
$listOutput = $recursiveCallbackData->traverseDatabaseSchema($schemas, function ($item) {
    if ($item instanceof DatabaseSchemaModel) {
        return "- {$item->schemaName}\n";
    } elseif ($item instanceof TableDataModel) {
        return "  - Table: {$item->tableName}\n";
    }
    return "";
});
echo "Text List Output:\n$listOutput\n";

// ---- Output as HTML List ----
$htmlOutput = "<ul>";
$htmlOutput .= $recursiveCallbackData->traverseDatabaseSchema($schemas, function ($item) {
    if ($item instanceof DatabaseSchemaModel) {
        return "<li>{$item->schemaName}<ul>";
    } elseif ($item instanceof TableDataModel) {
        return "<li>{$item->tableName}</li>";
    }
    return "";
});
$htmlOutput .= "</ul></li></ul>";

echo "\nHTML Output:\n$htmlOutput";
?>