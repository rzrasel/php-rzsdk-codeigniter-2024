<?php
namespace App\DatabaseSchema\Presentation\Views;
?>
<?php
use App\DatabaseSchema\Presentation\ViewModels\DatabaseSchemaViewModel;
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use App\DatabaseSchema\Domain\Models\TableDataModel;
?>
<?php
class DatabaseSchemaView {
    private $viewModel;

    public function __construct(DatabaseSchemaViewModel $viewModel) {
        $this->viewModel = $viewModel;
    }

    public function render(): void {
        /*$schema = $this->viewModel->getSchema($id);
        if ($schema) {
            echo "Schema Name: " . $schema->schemaName;
        } else {
            echo "Schema not found.";
        }*/
        /*$schema = new DatabaseSchemaModel();
        $schema->id = time();
        $schema->schemaName = "Database Schema Database";
        $schema->schemaVersion = "1.1.0";
        $schema->tablePrefix = "tbl_";
        $schema->databaseComment = "Database Schema Database";
        $schema->modifiedDate = date('Y-m-d H:i:s');
        $schema->createdDate = date('Y-m-d H:i:s');
        $this->viewModel->insertSchema($schema);*/

        // Create a new schema
        $schema = new DatabaseSchemaModel();
        $schema->id = time();
        $schema->schemaName = "database-schema-database";
        $schema->schemaVersion = "1.0";
        $schema->tablePrefix = "tbl_";
        $schema->databaseComment = "Test schema";
        $schema->modifiedDate = date('Y-m-d H:i:s');
        $schema->createdDate = date('Y-m-d H:i:s');

        // Add table data
        $tableData1 = new TableDataModel();
        $tableData1->schemaId = $schema->id;
        $tableData1->id = time();
        $tableData1->tableName = "table1";
        $tableData1->tableComment = "comment1";
        $tableData1->columnPrefix = "col_";
        $tableData1->modifiedDate = date('Y-m-d H:i:s');
        $tableData1->createdDate = date('Y-m-d H:i:s');
        //
        $tableData2 = new TableDataModel();
        $tableData2->schemaId = $schema->id;
        $tableData2->id = time();
        $tableData2->tableName = "table2";
        $tableData2->tableComment = "comment2";
        $tableData2->columnPrefix = "col_";
        $tableData2->modifiedDate = date('Y-m-d H:i:s');
        $tableData2->createdDate = date('Y-m-d H:i:s');
        $schema->tableData = [
            $tableData1,
            $tableData2,
        ];

        $this->viewModel->createSchema($schema);
    }
}
?>