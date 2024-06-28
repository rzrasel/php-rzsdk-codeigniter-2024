<?php
require_once("include.php");
?>
<?php
use RzSDK\Curl\Curl;
?>
<?php
$curl = new Curl("https://www.google.com");
$curl->exec(false);
?>