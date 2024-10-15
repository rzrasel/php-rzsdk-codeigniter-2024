<?php
namespace RzSDK\Import;
?>
<?php
$baseInclude = "model/";
require_once($baseInclude . "user-info-database-model.php");
require_once($baseInclude . "user-password-database-model.php");
require_once($baseInclude . "user-authentication-database-model.php");
require_once($baseInclude . "user-authentication-request-model.php");

require_once($baseInclude . "user-authentication-token-model.php");
require_once($baseInclude . "user-authentication-token-database-model.php");
?>
<?php
$baseInclude = "";
//require_once($baseInclude . "user-authentication-regex-validation.php");
require_once($baseInclude . "user-password-authentication-token.php");
?>