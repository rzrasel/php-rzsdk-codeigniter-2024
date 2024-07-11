<?php
namespace RzSDK\Import;
?>
<?php
$baseInclude = "model/";
require_once($baseInclude . "user-info-database-model.php");
require_once($baseInclude . "user-registration-database-model.php");
require_once($baseInclude . "user-password-database-model.php");
require_once($baseInclude . "user-registration-request-model.php");
require_once($baseInclude . "user-info-curl-response-model.php");
require_once($baseInclude . "user-authentication-token-database-model.php");
require_once($baseInclude . "user-registration-response-model.php");
?>
<?php
$baseInclude = "service/";
require_once($baseInclude . "user-registration-request-validation-service.php");
require_once($baseInclude . "user-registration-curl-user-fetch-service.php");
require_once($baseInclude . "user-registration-user-registration-database-service.php");
require_once($baseInclude . "user-registration-user-info-database-service.php");
require_once($baseInclude . "user-registration-user-password-database-service.php");
require_once($baseInclude . "user-authentication-token-database-service.php");
require_once($baseInclude . "user-authentication-token-key-list-generator.php");
?>
<?php
$baseInclude = "";
//require_once($baseInclude . "user-registration-process.php");
//require_once($baseInclude . "user-registration-regex-validation.php");
?>