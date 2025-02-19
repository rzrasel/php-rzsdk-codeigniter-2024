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
use App\DatabaseSchema\Data\Repositories\DatabaseSchemaRepositoryImpl;
use App\DatabaseSchema\Presentation\ViewModels\DatabaseSchemaViewModel;
use App\DatabaseSchema\Presentation\Views\DatabaseSchemaView;
?>
<?php
$repository = new DatabaseSchemaRepositoryImpl();
$viewModel = new DatabaseSchemaViewModel($repository);
$view = new DatabaseSchemaView($viewModel);
?>
<?php
if(!empty($_POST)) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = htmlspecialchars($value);
        $_POST[$key] = trim($value);
        if(empty($_POST[$key])) {
            $_POST[$key] = NULL;
        }
    }
    $view->createFromPostData($_POST);
}
?>
<table>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td>Database Schema Entry</td>
    </tr>
    <tr>
        <td></td>
    </tr>
</table>
<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="POST">
    <table>
        <tr>
            <td></td>
            <td width="10px"></td>
            <td></td>
        </tr>
        <tr>
            <td>Database Name:</td>
            <td></td>
            <td><input type="text" name="schema_name" id="schema_name" required="required" placeholder="Database schema name" /></td>
        </tr>
        <tr>
            <td>Database Version:</td>
            <td></td>
            <td><input type="text" name="schema_version" id="schema_version" required="required" placeholder="Database version" /></td>
        </tr>
        <tr>
            <td>Table Prefix:</td>
            <td></td>
            <td><input type="text" name="table_prefix" id="table_prefix" placeholder="Database prefix" /></td>
        </tr>
        <tr>
            <td>Database Comment:</td>
            <td></td>
            <td><input type="text" name="database_comment" id="database_comment" placeholder="Database comment" /></td>
        </tr>
        <tr>
            <td height="20px"></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><button type="submit">Submit</button></td>
        </tr>
    </table>
</form>