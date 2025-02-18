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
use RzSDK\Log\DebugLog;

?>
<?php
class DatabaseSchemaView {
    private $viewModel;

    public function __construct(DatabaseSchemaViewModel $viewModel) {
        $this->viewModel = $viewModel;
    }

    public function render(): void {

        $databaseSchemaList = array();
        $tableDataList = array();

        $dbSchemaData = $this->viewModel->getAllDatabaseSchema();
        if($dbSchemaData) {
            $databaseSchemaList = $dbSchemaData;
            //DebugLog::log($databaseSchemaList);
        } else {

            // Create a new schema
            $schemaData = $this->getDatabaseSchemaModel("database-schema-database");
            $schemaId = $schemaData->id;
            $tableData = $this->getTableDataModel($schemaId, "language_data");
            $tableDataList[] = $tableData;
            $tableData = $this->getTableDataModel($schemaId, "user_data");
            $tableDataList[] = $tableData;
            $schemaData->tableDataList = $tableDataList;
            $databaseSchemaList[] = $schemaData;
            $schemaData = $this->getDatabaseSchemaModel("database-schema-database-test");
            $databaseSchemaList[] = $schemaData;
        }
        //
        $repository = new TableDataRepositoryImpl();
        $viewModel = new TableDataViewModel($repository);
        $view = new TableDataView($viewModel);

        if(!empty($databaseSchemaList)) {
            foreach ($databaseSchemaList as $databaseSchema) {
                $databaseSchemaId = $databaseSchema->id;
                $databaseSchemaIdList[] = $databaseSchemaId;
                $this->viewModel->createSchema($databaseSchema);
                $tableDataList = $databaseSchema->tableDataList;
                if(!empty($tableDataList)) {
                    $view->render(null, $tableDataList);
                }
            }
        }

        /*$databaseSchemaIdList = array();
        foreach ($databaseSchemaList as $databaseSchema) {
            $databaseSchemaId = $databaseSchema->id;
            $databaseSchemaIdList[] = $databaseSchemaId;
            $this->viewModel->createSchema($databaseSchema);
        }*/
        /*$databaseSchemaIdList = array(
            "173986148774312621",
            "173986148774359334",
        );
        $view->render($databaseSchemaIdList);*/
    }

    private function getDatabaseSchemaModel($name, $prefix = "tbl_", $comment = null, $version = "1.1.1"): DatabaseSchemaModel {
        $uniqueIntId = new UniqueIntId();
        $model = new DatabaseSchemaModel();
        $model->id = $uniqueIntId->getId();
        $model->schemaName = $name;
        $model->schemaVersion = $version;
        $model->tablePrefix = $prefix;
        $model->databaseComment = $comment;
        $model->modifiedDate = date('Y-m-d H:i:s');
        $model->createdDate = date('Y-m-d H:i:s');
        return $model;
    }

    private function getTableDataModel($schemaId, $name, $comment = null, $prefix = null): TableDataModel {
        $uniqueIntId = new UniqueIntId();
        $model = new TableDataModel();
        $model->schemaId = $schemaId;
        $model->id = $uniqueIntId->getId();
        $model->tableName = $name;
        $model->tableComment = $comment;
        $model->columnPrefix = $prefix;
        $model->modifiedDate = date('Y-m-d H:i:s');
        $model->createdDate = date('Y-m-d H:i:s');
        return $model;
    }
}
?>