<?php
namespace App\DatabaseSchema\Presentation\Views;
?>
<?php
use App\DatabaseSchema\Presentation\ViewModels\ExtractDatabaseSchemaViewModel;
?>
<?php
class ExtractDatabaseSchemaView {
    private $viewModel;

    public function __construct(ExtractDatabaseSchemaViewModel $viewModel) {
        $this->viewModel = $viewModel;
    }

    public function getAllSchemaData(): array|bool {
        return $this->viewModel->getAllDatabaseSchemaData();
    }

    public function onExtractSchema($schemaData) {
        $this->viewModel->onExtractSchema($schemaData);
    }
}
?>
