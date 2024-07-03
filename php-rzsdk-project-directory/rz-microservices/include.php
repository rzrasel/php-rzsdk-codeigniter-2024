<?php
namespace RzSDK\Import;
?>
<?php
$baseInclude = "rz-sdk-library/";
//
$baseInclude = "rz-sdk-library/utils/";
require_once($baseInclude . "site-url.php");
require_once($baseInclude . "array-utils.php");
//
$baseInclude = "rz-sdk-library/database/";
require_once($baseInclude . "sqlite-connection.php");
//
$baseInclude = "rz-sdk-library/";
require_once($baseInclude . "curl/curl.php");
require_once($baseInclude . "validation/regular-validation.php");
require_once($baseInclude . "validation/email-validation.php");
require_once($baseInclude . "validation/password-validation.php");
//
$baseInclude = "rz-sdk-library/sql-query-builder/";
require_once($baseInclude . "module/select-column-sql.php");
require_once($baseInclude . "module/select-from-table-sql.php");
require_once($baseInclude . "module/select-where-sql.php");
require_once($baseInclude . "module/select-order-by-sql.php");
require_once($baseInclude . "module/select-limit-sql.php");
require_once($baseInclude . "module/select-offset-sql.php");
require_once($baseInclude . "insert-query.php");
require_once($baseInclude . "select-query.php");
require_once($baseInclude . "sql-query-builder.php");
//
$baseInclude = "rz-sdk-library/debug-log/";
require_once($baseInclude . "debug-log.php");
//
$baseInclude = "rz-sdk-library/";
require_once($baseInclude . "detect-client/client-device.php");
require_once($baseInclude . "detect-client/client-ip.php");
require_once($baseInclude . "date-time/date-diff-type.php");
require_once($baseInclude . "date-time/date-time.php");
require_once($baseInclude . "response/response.php");
require_once($baseInclude . "identification/unique-int-id.php");
?>
<?php
$baseInclude = "table-space/";
require_once($baseInclude . "db-type.php");
require_once($baseInclude . "db-user-table.php");
require_once($baseInclude . "user-registration-table.php");
require_once($baseInclude . "user-registration-table-query.php");
require_once($baseInclude . "user-info-table.php");
require_once($baseInclude . "user-info-table-query.php");
require_once($baseInclude . "user-password-table.php");
require_once($baseInclude . "user-password-table-query.php");
?>
<?php
$baseInclude = "http-query/";
require_once($baseInclude . "validation-type.php");
require_once($baseInclude . "user-request.php");
require_once($baseInclude . "user-registration-request.php");
?>
<?php
$baseInclude = "user/";
//require_once($baseInclude . "model/user-model.php");
?>
<?php
$baseInclude = "utils/";
require_once($baseInclude . "launch-response.php");
require_once($baseInclude . "prepare-validation-rules.php");
require_once($baseInclude . "build-validation-rules.php");
require_once($baseInclude . "user-auth-type.php");
?>
<?php
$baseInclude = "";
require_once($baseInclude . "curl-user-login.php");
require_once($baseInclude . "curl-user-registration.php");
require_once($baseInclude . "gen-database-schema.php");
?>
<?php
use RzSDK\URL\SiteUrl;
?>
<?php
defined("ROOT_URL") or define("ROOT_URL", SiteUrl::getBaseUrl());
defined("DB_PATH") or define("DB_PATH", "database");
defined("DB_FILE") or define("DB_FILE", "user-database.sqlite");
?>
<?php
function logPrint($message) {
    echo "<br />";
    if(is_array($message)) {
        echo "<pre>";
        print_r($message);
        echo "</pre>";
    } else {
        echo $message;
    }
    echo "<br />";
    //var_dump(debug_backtrace());
    /* echo "<pre>";
    print_r(debug_backtrace());
    echo "</pre>";
    echo "<br />"; */
}
?>
