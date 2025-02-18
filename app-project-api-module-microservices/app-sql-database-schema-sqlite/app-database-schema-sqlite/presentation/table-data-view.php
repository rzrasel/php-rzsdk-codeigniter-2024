<?php
namespace App\DatabaseSchema\Presentation\Views;
?>
<?php
use App\DatabaseSchema\Presentation\ViewModels\TableDataViewModel;
use App\DatabaseSchema\Data\Repositories\ColumnDataRepositoryImpl;
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
                $tableModel = $this->getTableDataModel($itemId, "language_data");
                $columnModel = $this->getColumnDataModel(
                    $tableModel->id,
                    "id", "VARCHAR(36)", true
                );
                $columnDataList[] = $columnModel;
                $tableModel->columnData = $columnDataList;
                $tableList[] = $tableModel;
                $tableModel = $this->getTableDataModel($itemId, "user_data");
                $tableList[] = $tableModel;
                //DebugLog::log($tableList);
            }
        }
        if(!empty($tableDataList)) {
            //DebugLog::log($tableDataList);
            foreach($tableDataList as $tableData) {
                $columnData = $this->populateColumnByTableData($tableData);
                if($columnData) {
                    $tableData->columnDataList = $columnData;
                }
                $tableList[] = $tableData;
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
                if(!empty($item->columnDataList)) {
                    $view->render(null, $item->columnDataList);
                }
            }
        }
        DebugLog::log("Table data view rendered");
        //
        /*$repository = new ColumnDataRepositoryImpl();
        $viewModel = new ColumnDataViewModel($repository);
        $view = new ColumnDataView($viewModel);*/
    }

    private function populateColumnByTableData(TableDataModel $tableData) {
        if(!empty($tableData)) {
            $columnId = 0;
            if(!empty($tableData->columnDataList)) {
                $columnId = ColumnDataModel::getIdByNameFilter("id", $tableData->columnDataList);
            }
            //DebugLog::log($columnId);
            //
            $columnDataList = array();
            if($tableData->tableName == "language_data") {
                $tableId = $tableData->id;
                $columnName = "id";
                $columnId = $this->getIdByColumnName($columnName, $tableData);
                $columnModel = $this->getColumnDataModel(
                    $tableId, $columnName,
                    "BIGINT(20)", $columnId, true
                );
                $columnDataList[] = $columnModel;
                //
                $columnName = "schema_name";
                $columnId = $this->getIdByColumnName($columnName, $tableData);
                $columnModel = $this->getColumnDataModel(
                    $tableId, $columnName,
                    "Text", $columnId, true
                );
                $columnDataList[] = $columnModel;
            }
            if(!empty($columnDataList)) {
                //DebugLog::log($columnDataList);
                return $columnDataList;
            }
        }
        return false;
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

    private function getColumnDataModel($tableId, $name, $dataType, $id = -1, $isNull = false, $defaultValue = null, $comment = null): ColumnDataModel {
        $uniqueIntId = new UniqueIntId();
        $columnId = $uniqueIntId->getId();
        if($id > 0) {
            $columnId = $id;
        }
        $model = new ColumnDataModel();
        $model->tableId = $tableId;
        $model->id = $columnId;
        $model->columnName = $name;
        $model->dataType = strtoupper($dataType);
        $model->isNullable = $isNull;
        $model->defaultValue = $defaultValue;
        $model->columnComment = $comment;
        $model->modifiedDate = date('Y-m-d H:i:s');
        $model->createdDate = date('Y-m-d H:i:s');
        return $model;
    }

    private function getIdByColumnName(string $columnName, TableDataModel $tableData): int {
        if(!empty($tableData)) {
            if(!empty($tableData->columnDataList)) {
                $columnId = ColumnDataModel::getIdByNameFilter("id", $tableData->columnDataList);
                return $columnId;
            }
        }
        return 0;
    }
}
?>