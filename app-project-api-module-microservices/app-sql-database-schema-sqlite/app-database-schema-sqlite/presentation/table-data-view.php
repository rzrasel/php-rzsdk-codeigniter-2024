<?php
namespace App\DatabaseSchema\Presentation\Views;
?>
<?php
use App\DatabaseSchema\Presentation\ViewModels\TableDataViewModel;
use App\DatabaseSchema\Domain\Models\TableDataModel;
?>
<?php
class TableDataView {
    private $viewModel;

    public function __construct(TableDataViewModel $viewModel) {
        $this->viewModel = $viewModel;
    }

    public function render($schemaId = -1, ?array $tableData = null): void {
    }
}
?>