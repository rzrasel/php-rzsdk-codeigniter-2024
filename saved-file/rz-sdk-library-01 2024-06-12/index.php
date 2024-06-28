<?php
require_once("run-autoloader.php");
?>
<?php
//use RzSDK\Autoloader\RunAutoloader;
use RzSDK\Id\UniqueIntId;

//new RunAutoloader(trim(trim(__DIR__, "/")));
$uniqueIntId = new UniqueIntId();
echo $uniqueIntId->getUserId();
?>
<?php
//echo $_SERVER["PHP_SELF"];
//echo dirname($_SERVER["PHP_SELF"]);
?>
<div style="margin: auto; width: 50%; border: 1px solid green; padding: 10px; border-radius: 10px;">
    <br />
    <br />
    <!-- <a href="<?= dirname($_SERVER["PHP_SELF"]) ?>/user-registration.php">User Registration</a> -->
    <a href="http://localhost/php-rzsdk-codeigniter/rz-sdk-library/user-registration.php">Laptop User Registration</a>
    <br />
    <br />
    <a href="http://localhost/php-project/rz-sdk-library/user-registration.php">Desktop User Registration</a>
</div>