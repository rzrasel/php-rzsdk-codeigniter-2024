<?php
// public/index.php
//require __DIR__ . '/../vendor/autoload.php';
require_once("include.php");
?>
<?php
use App\DatabaseSchema\Data\Repositories\DatabaseSchemaRepositoryImpl;
use App\DatabaseSchema\Presentation\ViewModels\DatabaseSchemaViewModel;
use App\DatabaseSchema\Presentation\Views\DatabaseSchemaView;
?>
<?php
$repository = new DatabaseSchemaRepositoryImpl();
$viewModel = new DatabaseSchemaViewModel($repository);
$view = new DatabaseSchemaView($viewModel);

$view->render();
?>
<?php
?>
