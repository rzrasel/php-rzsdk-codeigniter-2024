<?php
// public/index.php
//require __DIR__ . '/../vendor/autoload.php';
require_once("include.php");
?>
<?php
use App\Config\Database;
use App\Data\Repositories\DatabaseSchemaRepository;
use App\Presentation\ViewModels\DatabaseSchemaViewModel;
use App\Presentation\Views\DatabaseSchemaView;
use App\Domain\Entities\DatabaseSchema;
?>
<?php
// Get database connection
$db = Database::getInstance("database.sqlite3");

// Create a new DatabaseSchema domain model
$schema = new DatabaseSchema();
$schema->schemaName = "MySchema";
$schema->schemaVersion = "1.0";
$schema->tablePrefix = "tbl_";
$schema->modifiedDate = date('Y-m-d H:i:s');
$schema->createdDate = date('Y-m-d H:i:s');

// Save the schema using the repository
$repository = new DatabaseSchemaRepository($db);
$repository->save($schema);

// Output the ID of the newly inserted record
echo "New schema inserted with ID: " . $schema->id;
?>
<?php
$db = Database::getInstance("database.sqlite3");
$repository = new DatabaseSchemaRepository($db);
$viewModel = new DatabaseSchemaViewModel($repository);
$view = new DatabaseSchemaView($viewModel);

$view->render(1); // Render schema with ID 1
?>