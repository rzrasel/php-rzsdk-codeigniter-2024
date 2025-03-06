<?php
namespace App\DatabaseSchema\Presentation\Views;
?>
<?php
use App\DatabaseSchema\Presentation\ViewModels\ExtractDatabaseSchemaViewModel;
use RzSDK\Log\DebugLog;
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
        //DebugLog::log($schemaData);
        $this->viewModel->onExtractSchema($schemaData);
    }
}
?>
