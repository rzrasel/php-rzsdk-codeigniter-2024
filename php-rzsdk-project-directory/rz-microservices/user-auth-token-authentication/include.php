<?php
namespace RzSDK\Import;
?>
<?php
$baseInclude = "model/";
require_once($baseInclude . "user-auth-token-authentication-request-model.php");
require_once($baseInclude . "user-auth-token-authentication-response-model.php");
//require_once($baseInclude . "test.php");
?>
<?php
$baseInclude = "service/";
require_once($baseInclude . "user-auth-token-authentication-request-validation-service.php");
require_once($baseInclude . "user-auth-token-authentication-database-validation-service.php");
?>