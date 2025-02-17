<?php
namespace App\DatabaseSchema\Presentation\Views;
?>
<?php
use App\DatabaseSchema\Data\Repositories\ColumnDataRepositoryImpl;
use App\DatabaseSchema\Presentation\ViewModels\TableDataViewModel;
use App\DatabaseSchema\Presentation\ViewModels\ColumnDataViewModel;
use App\DatabaseSchema\Presentation\Views\ColumnDataView;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use App\DatabaseSchema\Domain\Models\ColumnDataModel;
use RzSDK\Identification\UniqueIntId;
use RzSDK\Log\DebugLog;

?>
<?php
class TableDataView {
    private $viewModel;

    public function __construct(TableDataViewModel $viewModel) {
        $this->viewModel = $viewModel;
    }

    public function render(array $schemaIdList = null, array $tableDataList = null): void {
        $tableList = array();
        $columnDataList = array();
        if(!empty($schemaIdList)) {
            //echo "<pre>" . print_r($schemaId, true) . "</pre>";
            $uniqueIntId = new UniqueIntId();
            foreach($schemaIdList as $itemId) {
                // Add table data
                $tableModel = $this->getTableModel($itemId, "language_data");
                $columnModel = $this->getColumnModel(
                    $tableModel->id,
                    "id", "VARCHAR(36)", true
                );
                $columnDataList[] = $columnModel;
                $tableModel->columnData = $columnDataList;
                $tableList[] = $tableModel;
                $tableModel = $this->getTableModel($itemId, "user_data");
                $tableList[] = $tableModel;
                //DebugLog::log($tableList);
            }
        }
        //
        $repository = new ColumnDataRepositoryImpl();
        $viewModel = new ColumnDataViewModel($repository);
        $view = new ColumnDataView($viewModel);
        if(!empty($tableList)) {
            foreach ($tableList as $item) {
                //DebugLog::log($item);
                $this->viewModel->createTable($item);
                if(!empty($item->columnData)) {
                    $view->render(null, $item->columnData);
                }
            }
        }
        DebugLog::log("Table data view rendered");
        //
        /*$repository = new ColumnDataRepositoryImpl();
        $viewModel = new ColumnDataViewModel($repository);
        $view = new ColumnDataView($viewModel);*/
    }

    private function getTableModel($schemaId, $name, $comment = null, $prefix = null): TableDataModel {
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

    private function getColumnModel($tableId, $name, $dataType, $isNull = false, $defaultValue = null, $comment = null): ColumnDataModel {
        $uniqueIntId = new UniqueIntId();
        $model = new ColumnDataModel();
        $model->tableId = $tableId;
        $model->id = $uniqueIntId->getId();
        $model->columnName = $name;
        $model->dataType = $dataType;
        $model->isNullable = $isNull;
        $model->defaultValue = $defaultValue;
        $model->columnComment = $comment;
        $model->modifiedDate = date('Y-m-d H:i:s');
        $model->createdDate = date('Y-m-d H:i:s');
        return $model;
    }
}
?>