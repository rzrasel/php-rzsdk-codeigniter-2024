<?php
require_once("include.php");
?>
<?php
use RzSDK\Log\DebugLog;
use RzSDK\URL\SiteUrl;
use RzSDK\User\Authentication\Token\CurlUserAuthTokenAuthentication;
use RzSDK\User\Registration\CurlUserRegistration;
use RzSDK\User\Login\CurlUserLogin;
use RzSDK\Device\ClientDevice;
use RzSDK\Device\ClientIp;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Encryption\JwtManager;
use RzSDK\Generator\GenDatabaseSchema;
?>
<?php
/* $clientDevice = new ClientDevice();
//DebugLog::log($clientDevice->device());
DebugLog::log($clientDevice->getOs());
DebugLog::log($clientDevice->getDevice());
$detect = ClientIp::ip();
DebugLog::log($detect);
$str = "Hello";
DebugLog::log(sha1($str)); */

/* $className = "Client1Device";
$filePath = preg_replace("/([a-z|0-9])([A-Z])/", "$1_$2", $className);
$filePath = strtolower($filePath);
DebugLog::log($filePath); */

/* $className = "ClientCatDevice";

// lowercase first letter
$className[0] = strtolower($className[0]);
$pathName = "";

$len = strlen($className);
for ($i = 0; $i < $len; ++$i) {
    // see if we have an uppercase character and replace
    if (ord($className[$i]) > 64 && ord($className[$i]) < 91) {
        $pathName .= "_" . strtolower($className[$i]);
        // increase length of class and position
        /=* ++$len;
        ++$i; *=/
        //++$i;
    } else {
        $pathName .= strtolower($className[$i]);
    }
}
DebugLog::log($pathName); */

/* $testString = "This is text I don't care about | 12.2 minutes ago | 7.3 seconds ago";
preg_match("/\b[0-9.]+? \w+?\b/i", $testString, $matches);
DebugLog::log($matches);

$time_map = array(
'seconds' => 'secs',
'minutes' => 'mins',
'hours'   => 'hrs',
'days'    => 'days',
);

$data = array(
    "Some text I don't care about | 2.3 seconds ago | 12.2 minutes ago",
    "Some text I don't care about | 5.2 minutes ago",
    "Some text I don't care about | 7.0 hours ago",
    "Some text I don't care about | 1.9 days ago",
);

foreach($data as $line) {
    $time_data = preg_replace_callback("/(.*|\s*)([0-9]+\.[0-9]+) (\w+) ago/",  function($matches) use($time_map) {
        DebugLog::log($matches);
        return $matches[2] . " " . $time_map[$matches[3]];
    }, $line);
    DebugLog::log($time_data);
} */
?>
<?php
/*
$firstCharacter = "[{<>}] [(<>)] {{Friday}}t{od{a}y{{Sunday}}";
$firstCharacter = "{<#>} [{<>}] {(<=)} [(<>)] [{<>}] [(<>)] {{Friday}}t{od{a}y{{Sunday}}";
$firstCharacter = "{(<>)} Regular expression tester with syntax highlighting";
$firstCharacter = "    {<>} Regular expression tester with syntax highlighting";
//$firstCharacter = strtok($firstCharacter, " ");
//$firstCharacter = substr($firstCharacter, 0, strpos($firstCharacter, " "));
DebugLog::log($firstCharacter);
$strPos = strpos($firstCharacter, "{<>}");
DebugLog::log($strPos);
$strPos = $strPos + strlen("{<>}");
$subStr = substr($firstCharacter, $strPos, strlen($firstCharacter));
DebugLog::log($subStr);
$pattern = "/(<|<>)/i";
$pattern = "/{{(?<inner>.*?)}}|(?<outer>[^{}]*(?:{(?!{)[^{}]*|}(?!})[^{}]*)*)/";
$pattern = "/(?<=\()(.+)(?=\))/is";
$pattern = "/\(([^\)]*)\)/";
$pattern = "/\(([^\)]*)\)/";
$pattern = "/\((.*?)\)/si";
$pattern = "/\[\((.*?)\)\]/si";
$pattern = "/\[\((.*?)\)\]|\[\{(.*?)\}\]/si";
$pattern = array();
$pattern[] = "\{(.*?)\}"; //{}
$pattern[] = "\[\{(.*?)\}\]"; //[{}]
$pattern[] = "\[\((.*?)\)\]"; //[()]
$pattern[] = "\{\((.*?)\)\}"; //{()}
$regexPattern = implode("|", $pattern);
//$pattern = "/{$pattern1}|{$pattern2}|{$pattern3}/si";
$pattern = "/^\s+{$regexPattern}$/si";
//$pattern = "/{$pattern[0]}|{$pattern[1]}|{$pattern[2]}|{$pattern[3]}/si";
//if(preg_match($pattern, $firstCharacter, $matches)) {
//if(preg_match_all($pattern, $firstCharacter, $matches)) {
if(preg_match($pattern, $firstCharacter, $matches)) {
    DebugLog::log($matches);
    DebugLog::log($matches[1]);
} else {
    DebugLog::log("Not Found");
}
/-*if(preg_match_all($pattern, $firstCharacter, $matches)) {
    //DebugLog::log($matches[0]);
    //$operator = $matches[1];
    //$value = ltrim(substr($value, 1));
    DebugLog::log(array_filter($matches["outer"]));
    DebugLog::log(array_filter($matches["inner"]));
    DebugLog::log($matches);
} else {
    DebugLog::log("Not Found");
}*-/
*/
?>
<?php
$isPrint = false;
//$isPrint = true;
$genDatabaseSchema = new GenDatabaseSchema($isPrint);
?>
<?php
/* echo "getFullUrl() " . SiteUrl::getFullUrl();
echo "<br />";
echo "getUrlOnly() " . SiteUrl::getUrlOnly();
echo "<br />";
echo "getBaseUrl() " . SiteUrl::getBaseUrl();
echo "<br />";
echo "<br />"; */
$curlUserRegistration = new CurlUserRegistration(SiteUrl::getBaseUrl());
//DebugLog::log(get_object_vars($curlUserRegistration));
//$curlUserRegistration->example();
?>
<?php
/*function getArrayByValue($value, array $array) {
    if(empty($array)) {
        return null;
    }
    $result = array();
    $isFound = false;
    foreach($array as $key => $item) {
        if($value == $item) {
            $isFound = true;
            $result[$key] = $item;
        }
    }
    return ($isFound) ? $result : null;
}
$a=array("a"=>"red", "b"=>"green", "c"=>"blue", "d"=>"red");
$data = array_search("red",$a);
DebugLog::log($data);
$data = getArrayByValue("red", $a);
DebugLog::log($data);*/
?>
<?php
?>
<?php
//$curlUserLogin = new CurlUserLogin(SiteUrl::getBaseUrl());
?>
<?php
$curlUserAuthTokenAuthentication = new CurlUserAuthTokenAuthentication(SiteUrl::getBaseUrl());
?>
<?php
echo "<br />";
echo $_SERVER["PHP_SELF"];
echo "<br />";
echo dirname($_SERVER["PHP_SELF"]);
echo "<br />";
?>
<div style="margin: auto; width: 50%; border: 1px solid green; padding: 10px; border-radius: 10px;">
    <br />
    <br />
    <!-- <a href="<?= dirname($_SERVER["PHP_SELF"]) ?>/user-registration.php">User Registration</a> -->
    <a href="<?= ROOT_URL; ?>">Laptop User Registration</a>
    <br />
    <br />
    <a href="http://localhost/php-rzsdk-codeigniter/rz-microservices/user-registration/user-registration.php">Desktop User Registration Process</a>
    <br />
    <br />
    <br />
    <br />
    <a href="http://localhost/php-rzsdk-codeigniter/rz-microservices/user-registration/user-registration.php">Desktop User Registration</a>
</div>