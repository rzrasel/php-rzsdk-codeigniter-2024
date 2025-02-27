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
}
?>
