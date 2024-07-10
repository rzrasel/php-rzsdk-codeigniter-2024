<?php
namespace RzSDK\Import;
?>
<?php
defined("RZ_SDK_BASEPATH") or define("RZ_SDK_BASEPATH", trim(trim(__DIR__, "/")));
defined("RZ_SDK_LIB_ROOT_DIR") or define("RZ_SDK_LIB_ROOT_DIR", "rz-sdk-library");
defined("RZ_SDK_DIR_FILE_PATH") or define("RZ_SDK_DIR_FILE_PATH", "directory-path-file.json");
$rzSdkFolder = "rz-sdk-library";
defined("RZ_SDK_WRAPPER") or define("RZ_SDK_WRAPPER", str_replace("\\", "/", $rzSdkFolder));
?>
<?php
$baseInclude = "rz-sdk-library/";
//
$baseInclude = "rz-sdk-library/autoloader/";
require_once($baseInclude . "autoloader.php");
//
/*$baseInclude = "rz-sdk-library/utils/";
require_once($baseInclude . "site-url.php");
require_once($baseInclude . "array-utils.php");*/
//
/*$baseInclude = "rz-sdk-library/database/";
require_once($baseInclude . "sqlite-connection.php");*/
//
/*$baseInclude = "rz-sdk-library/";
require_once($baseInclude . "curl/curl.php");
require_once($baseInclude . "validation/regular-validation.php");
require_once($baseInclude . "validation/email-validation.php");
require_once($baseInclude . "validation/password-validation.php");*/
//
/*$baseInclude = "rz-sdk-library/sql-query-builder/";
require_once($baseInclude . "module/select-column-sql.php");
require_once($baseInclude . "module/select-from-table-sql.php");
require_once($baseInclude . "module/select-join-sql.php");
require_once($baseInclude . "module/select-where-sql.php");
require_once($baseInclude . "module/select-group-by-sql.php");
require_once($baseInclude . "module/select-order-by-sql.php");
require_once($baseInclude . "module/select-limit-sql.php");
require_once($baseInclude . "module/select-offset-sql.php");
require_once($baseInclude . "module/update-set-sql.php");
require_once($baseInclude . "select-query.php");
require_once($baseInclude . "insert-query.php");
require_once($baseInclude . "update-query.php");
require_once($baseInclude . "delete-query.php");
require_once($baseInclude . "sql-query-builder.php");*/
//
/*$baseInclude = "rz-sdk-library/debug-log/";
require_once($baseInclude . "debug-log.php");*/
//
/*$baseInclude = "rz-sdk-library/";
require_once($baseInclude . "detect-client/client-device.php");
require_once($baseInclude . "detect-client/client-ip.php");
require_once($baseInclude . "date-time/date-diff-type.php");
require_once($baseInclude . "date-time/date-time.php");
require_once($baseInclude . "identification/unique-int-id.php");
require_once($baseInclude . "response/response.php");*/
?>
<?php
$baseInclude = "table-space/db-properties/";
require_once($baseInclude . "db-type.php");
require_once($baseInclude . "db-table-property.php");
require_once($baseInclude . "db-column-properties.php");
require_once($baseInclude . "db-column-constraints-properties.php");
require_once($baseInclude . "db-column-constraint-type.php");
require_once($baseInclude . "db-sql-query-generator.php");
$baseInclude = "table-space/";
require_once($baseInclude . "db-user-table.php");
require_once($baseInclude . "user-registration-table.php");
require_once($baseInclude . "user-registration-table-query.php");
require_once($baseInclude . "user-info-table.php");
require_once($baseInclude . "user-info-table-query.php");
require_once($baseInclude . "user-password-table.php");
require_once($baseInclude . "user-password-table-query.php");
require_once($baseInclude . "user-login-auth-log-table.php");
require_once($baseInclude . "user-login-auth-log-table-query.php");
?>
<?php
$baseInclude = "http-query/";
require_once($baseInclude . "validation-type.php");
require_once($baseInclude . "user-info-request.php");
require_once($baseInclude . "user-registration-request.php");
require_once($baseInclude . "user-login-request.php");
require_once($baseInclude . "user-authentication-request.php");
require_once($baseInclude . "user-auth-token-authentication-request.php");
?>
<?php
$baseInclude = "user-info/";
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
require_once($baseInclude . "curl-user-auth-token-authentication.php");
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
