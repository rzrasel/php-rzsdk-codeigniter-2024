<?php
namespace App\DatabaseSchema\Presentation\Views;
?>
<?php
use App\DatabaseSchema\Presentation\ViewModels\DeleteDatabaseSchemaQueryViewModel;
?>
<?php
class DeleteDatabaseSchemaQueryView {
    private $viewModel;

    public function __construct(DeleteDatabaseSchemaQueryViewModel $viewModel) {
        $this->viewModel = $viewModel;
    }

    public function onRunRawQuery($postData) {
        $this->viewModel->onRunRawQuery($postData);
    }

    public function onDeleteDatabaseSchemaQuery($postData) {
        $this->viewModel->onDeleteDatabaseSchemaQuery($postData);
    }
}
?>