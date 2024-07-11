<?php
namespace RzSDK\Import;
?>
<?php
$baseInclude = "model/";
require_once($baseInclude . "user-info-database-model.php");
require_once($baseInclude . "user-registration-database-model.php");
require_once($baseInclude . "user-password-database-model.php");
require_once($baseInclude . "user-registration-request-model.php");
?>
<?php
$baseInclude = "service/";
require_once($baseInclude . "user-registration-request-validation-service.php");
?>
<?php
$baseInclude = "";
//require_once($baseInclude . "user-registration-process.php");
//require_once($baseInclude . "user-registration-regex-validation.php");
?>