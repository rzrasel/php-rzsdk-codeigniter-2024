<?php
namespace App\DatabaseSchema\Presentation\Views;
?>
<?php
use App\DatabaseSchema\Data\Repositories\ColumnDataRepositoryImpl;
use App\DatabaseSchema\Presentation\ViewModels\TableDataViewModel;
use App\DatabaseSchema\Presentation\ViewModels\ColumnDataViewModel;
use App\DatabaseSchema\Presentation\Views\ColumnDataView;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use RzSDK\Identification\UniqueIntId;
?>
<?php
class TableDataView {
    private $viewModel;

    public function __construct(TableDataViewModel $viewModel) {
        $this->viewModel = $viewModel;
    }

    public function render(array $schemaIdList = null, array $tableDataList = null): void {
        $tableList = array();
        if(!empty($schemaIdList)) {
            //echo "<pre>" . print_r($schemaId, true) . "</pre>";
            $uniqueIntId = new UniqueIntId();
            foreach($schemaIdList as $itemId) {
                // Add table data
                $tableModel = $this->getModel($itemId, "language_data");
                $tableList[] = $tableModel;
                $tableModel = $this->getModel($itemId, "user_data");
                $tableList[] = $tableModel;
            }
        }
        if(!empty($tableList)) {
            foreach ($tableList as $item) {
                $this->viewModel->createTable($item);
            }
        }
        //
        $repository = new ColumnDataRepositoryImpl();
        $viewModel = new ColumnDataViewModel($repository);
        $view = new ColumnDataView($viewModel);
    }

    private function getModel($id, $name, $comment = null, $prefix = null): TableDataModel {
        $uniqueIntId = new UniqueIntId();
        $model = new TableDataModel();
        $model->schemaId = $id;
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