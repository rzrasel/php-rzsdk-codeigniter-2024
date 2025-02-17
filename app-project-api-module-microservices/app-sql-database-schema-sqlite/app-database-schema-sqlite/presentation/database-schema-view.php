<?php
namespace App\DatabaseSchema\Presentation\Views;
?>
<?php
use App\DatabaseSchema\Presentation\ViewModels\DatabaseSchemaViewModel;
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use App\DatabaseSchema\Data\Repositories\TableDataRepositoryImpl;
use App\DatabaseSchema\Presentation\ViewModels\TableDataViewModel;
use App\DatabaseSchema\Presentation\Views\TableDataView;
use RzSDK\Identification\UniqueIntId;
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

        $uniqueIntId = new UniqueIntId();

        $databaseSchemaList = array();

        // Create a new schema
        $schema = new DatabaseSchemaModel();
        $schema->id = $uniqueIntId->getId();
        $schema->schemaName = "database-schema-database";
        $schema->schemaVersion = "v-1.1.1";
        $schema->tablePrefix = "tbl_";
        $schema->databaseComment = "Test schema";
        $schema->modifiedDate = date('Y-m-d H:i:s');
        $schema->createdDate = date('Y-m-d H:i:s');
        $databaseSchemaList[] = $schema;
        //
        $schema = new DatabaseSchemaModel();
        $schema->id = $uniqueIntId->getId();
        $schema->schemaName = "database-schema-database-test";
        $schema->schemaVersion = "v-1.1.1";
        $schema->tablePrefix = "tbl_";
        $schema->databaseComment = "Test schema";
        $schema->modifiedDate = date('Y-m-d H:i:s');
        $schema->createdDate = date('Y-m-d H:i:s');
        $databaseSchemaList[] = $schema;

        // Add table data
        /*$tableData1 = new TableDataModel();
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
        ];*/
        //
        $repository = new TableDataRepositoryImpl();
        $viewModel = new TableDataViewModel($repository);
        $view = new TableDataView($viewModel);

        $databaseSchemaIdList = array();
        foreach ($databaseSchemaList as $databaseSchema) {
            $databaseSchemaId = $databaseSchema->id;
            $databaseSchemaIdList[] = $databaseSchemaId;
            //$this->viewModel->createSchema($databaseSchema);
        }
        $databaseSchemaIdList = array(
            "173979361554730734",
            "173979361554790749",
        );
        $view->render($databaseSchemaIdList);
    }
}
?>