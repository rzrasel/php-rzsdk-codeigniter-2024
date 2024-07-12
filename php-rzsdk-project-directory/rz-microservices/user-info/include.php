<?php
namespace RzSDK\Import;
?>
<?php
$baseInclude = "model/";
require_once($baseInclude . "user-registration-database-model.php");
require_once($baseInclude . "user-info-database-model.php");
require_once($baseInclude . "user-info-request-model.php");
require_once($baseInclude . "user-info-response-model.php");
?>
<?php
$baseInclude = "service/";
require_once($baseInclude . "user-info-request-validation-service.php");
require_once($baseInclude . "user-info-user-info-database-service.php");
require_once($baseInclude . "user-info-user-registration-database-service.php");
?>
<?php
$baseInclude = "";
//require_once($baseInclude . "user-regex-validation.php");
?>