<?php
// app/Presentation/Views/DatabaseSchemaView.php
namespace App\Presentation\Views;
?>
<?php
use App\Presentation\ViewModels\DatabaseSchemaViewModel;
?>
<?php
class DatabaseSchemaView {
    private $viewModel;

    public function __construct(DatabaseSchemaViewModel $viewModel) {
        $this->viewModel = $viewModel;
    }

    public function render(int $id): void {
        $schema = $this->viewModel->getSchema($id);
        if ($schema) {
            echo "Schema Name: " . $schema->schemaName;
        } else {
            echo "Schema not found.";
        }
    }
}
?>