<?php
namespace App\DatabaseSchema\Presentation\Views;
?>
<?php
use App\DatabaseSchema\Presentation\ViewModels\ColumnDataViewModel;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use App\DatabaseSchema\Data\Repositories\ColumnKeyRepositoryImpl;
use App\DatabaseSchema\Presentation\ViewModels\ColumnKeyViewModel;
use App\DatabaseSchema\Presentation\Views\ColumnKeyView;
use RzSDK\Identification\UniqueIntId;
use RzSDK\Log\DebugLog;

?>
<?php
class ColumnDataView {
    private $viewModel;

    public function __construct(ColumnDataViewModel $viewModel) {
        $this->viewModel = $viewModel;
    }

    public function getAllTableDataGroupBySchema(): array|bool {
        return $this->viewModel->getAllTableDataGroupBySchema();
    }

    public function createFromPostData($postData): void {
        $this->viewModel->createFromPostData($postData);
    }

    public function updateFromPostData($postData): void {
        $this->viewModel->updateFromPostData($postData);
    }
    //

    public function render(array $tableIdList = null, array $columnDataList = null): void {
        //DebugLog::log($tableIdList);
        /*$tableDataList = array();
        if(!empty($schemaId)) {
            //echo "<pre>" . print_r($schemaId, true) . "</pre>";
            $uniqueIntId = new UniqueIntId();
            foreach($schemaId as $item) {
                // Add table data
                $tableData = new TableDataModel();
                $tableData->schemaId = $item;
                $tableData->id = $uniqueIntId->getId();
                $tableData->tableName = "table1";
                $tableData->tableComment = "comment1";
                $tableData->columnPrefix = "";
                $tableData->modifiedDate = date('Y-m-d H:i:s');
                $tableData->createdDate = date('Y-m-d H:i:s');
                $tableDataList[] = $tableData;

                $tableData = new TableDataModel();
                $tableData->schemaId = $item;
                $tableData->id = $uniqueIntId->getId();
                $tableData->tableName = "table2";
                $tableData->tableComment = "comment1";
                $tableData->columnPrefix = "";
                $tableData->modifiedDate = date('Y-m-d H:i:s');
                $tableData->createdDate = date('Y-m-d H:i:s');
                $tableDataList[] = $tableData;
            }
        }
        foreach($tableDataList as $tableData) {
            $this->viewModel->createTable($tableData);
        }*/
        $repository = new ColumnKeyRepositoryImpl();
        $viewModel = new ColumnKeyViewModel($repository);
        $view = new ColumnKeyView($viewModel);
        //
        if(!empty($columnDataList)) {
            foreach($columnDataList as $columnData) {
                //DebugLog::log($columnData);
                $this->viewModel->createTable($columnData);
            }
        }
    }
}
?>