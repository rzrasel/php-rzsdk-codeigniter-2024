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