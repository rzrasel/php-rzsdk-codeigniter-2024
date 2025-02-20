<?php
/*$workingDir = __DIR__;
$workingDirName = basename($workingDir);
defined("CONST_STARTING_PATH") or define("CONST_STARTING_PATH", $workingDir);
defined("CONST_WORKING_DIR_NAME") or define("CONST_WORKING_DIR_NAME", $workingDirName);*/
?>
<?php
// public/index.php
//require __DIR__ . '/../vendor/autoload.php';
require_once("include.php");
?>
<?php
use App\DatabaseSchema\Data\Repositories\CompositeKeyRepositoryImpl;
use App\DatabaseSchema\Presentation\ViewModels\CompositeKeyViewModel;
use App\DatabaseSchema\Presentation\Views\CompositeKeyView;
use RzSDK\Log\DebugLog;
?>
<?php
$repository = new CompositeKeyRepositoryImpl();
$viewModel = new CompositeKeyViewModel($repository);
$view = new CompositeKeyView($viewModel);
?>

