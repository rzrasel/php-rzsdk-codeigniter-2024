<?php
namespace App\DatabaseSchema\Presentation\Views;
?>
<?php
use App\DatabaseSchema\Presentation\ViewModels\CompositeKeyViewModel;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use RzSDK\Identification\UniqueIntId;
?>
<?php
class CompositeKeyView {
    private $viewModel;

    public function __construct(CompositeKeyViewModel $viewModel) {
        $this->viewModel = $viewModel;
    }

    public function getAllTableDataGroupByTable(): array|bool {
        return $this->viewModel->getAllTableDataGroupByTable();
    }

    public function createFromPostData($postData, ?array $schemaDataList = array()): void {
        $this->viewModel->createFromPostData($postData, $schemaDataList);
    }
    //

    public function render(array $schemaId = null, array $tableData = null): void {
        $tableDataList = array();
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
        }
    }
}
?>