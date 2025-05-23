<?php
require_once("include.php");
?>
<?php

use RzSDK\DateTime\DateTime;
use RzSDK\URL\SiteUrl;
use RzSDK\Database\DbType;
use RzSDK\Log\DebugLog;
//
use RzSDK\Database\Schema\TblDatabaseSchemaQuery;
use RzSDK\Database\Schema\TblTableDataQuery;
use RzSDK\Database\Schema\TblColumnDataQuery;
use RzSDK\Database\Schema\TblColumnKeyQuery;
use RzSDK\Database\Schema\TblColumnCompositeKeyQuery;
//
use RzSDK\Database\Schema\TblLanguageDataQuery;
use RzSDK\Database\Schema\TblUserDataQuery;
use RzSDK\Database\Schema\TblUserDataExtQuery;
use RzSDK\Database\Schema\TblUserManualAccountQuery;
use RzSDK\Database\Schema\TblUserSocialAccountQuery;
use RzSDK\Database\Schema\TblUserOpenAccountQuery;
use RzSDK\Database\Schema\TblUserPasswordQuery;
use RzSDK\Database\Schema\TblUserEmailQuery;
use RzSDK\Database\Schema\TblUserMobileQuery;
use RzSDK\Database\Schema\TblUserTokenQuery;
use RzSDK\Database\Schema\TblUserSessionQuery;
use RzSDK\Database\Schema\TblUserSysRoleQuery;
use RzSDK\Database\Schema\TblUserSysPermissionQuery;
use RzSDK\Database\Schema\TblUserSysRolePermissionQuery;
use RzSDK\Database\Schema\TblUserSysUserRoleQuery;
use RzSDK\Database\Schema\TblUserLastActivityQuery;
use RzSDK\Database\Schema\TblUserLoginAttemptQuery;
use RzSDK\Database\Schema\TblUserPasswordResetQuery;
use RzSDK\Database\Schema\TblUserMfaSettingsQuery;
use RzSDK\Database\Schema\TblUserPreferencesQuery;
use RzSDK\Database\Schema\TblUserConsentsQuery;
use RzSDK\Database\Schema\TblUserWebhooksQuery;
use RzSDK\Database\Schema\TblUserProfileQuery;
use RzSDK\Database\Schema\TblUserProfileImageQuery;

?>
<?php

class SchemaTblLanguage {
    private $tableQuery;

    public function __construct()
    {
        $dbType = DbType::SQLITE;
        //$this->tableQuery = new TblLanguageQuery($dbType);
    }

    public function dropQuery()
    {
        return $this->tableQuery->dropQuery();
    }

    public function createQuery()
    {
        return $this->tableQuery->execute();
    }

    public function deleteQuery()
    {
        return $this->tableQuery->deleteQuery();
    }
}

//$tblLanguageSchema = new SchemaTblLanguage();
?>
<?php
$dbType = DbType::SQLITE;
$databaseSchemaList = array(
    new TblDatabaseSchemaQuery($dbType),
    new TblTableDataQuery($dbType),
    new TblColumnDataQuery($dbType),
    new TblColumnKeyQuery($dbType),
    new TblColumnCompositeKeyQuery($dbType),
    //
    new TblLanguageDataQuery($dbType),
    new TblUserDataQuery($dbType),
    new TblUserDataExtQuery($dbType),
    new TblUserManualAccountQuery($dbType),
    new TblUserSocialAccountQuery($dbType),
    new TblUserOpenAccountQuery($dbType),
    new TblUserPasswordQuery($dbType),
    new TblUserEmailQuery($dbType),
    new TblUserMobileQuery($dbType),
    new TblUserTokenQuery($dbType),
    new TblUserSysRoleQuery($dbType),
    new TblUserSysPermissionQuery($dbType),
    new TblUserSysRolePermissionQuery($dbType),
    new TblUserSysUserRoleQuery($dbType),
    new TblUserSessionQuery($dbType),
    new TblUserLastActivityQuery($dbType),
    new TblUserLoginAttemptQuery($dbType),
    new TblUserPasswordResetQuery($dbType),
    new TblUserMfaSettingsQuery($dbType),
    new TblUserPreferencesQuery($dbType),
    new TblUserConsentsQuery($dbType),
    new TblUserWebhooksQuery($dbType),
    new TblUserProfileQuery($dbType),
    new TblUserProfileImageQuery($dbType),
);
?>
<br/>
<br/>
-- SQLite Database DATE CREATED: 2025-01-30, DATE MODIFIED: <?= DateTime::getCurrentDateTime("Y-m-d"); ?> VERSION: 1.1.1
<br/>
<br/>
<br/>
<?php
foreach (array_reverse($databaseSchemaList) as $databaseSchema) {
    echo $databaseSchema->dropQuery();
    echo "<br />\n";
}
?>
<br/>
<pre>
<?php
foreach ($databaseSchemaList as $databaseSchema) {
    echo $databaseSchema->execute();
    echo "<br />";
    echo "<br />";
}
?>
</pre>
<?php
foreach (array_reverse($databaseSchemaList) as $databaseSchema) {
    echo $databaseSchema->deleteQuery();
    echo "<br />";
}
?>
<br/>
<br/>
<br/>
<br/>
