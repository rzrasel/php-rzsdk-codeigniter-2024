<?php
require_once("../include.php");
require_once("include.php");
?>
<?php
use RzSDK\Utils\ObjectPropertyWizard;
use RzSDK\Log\DebugLog;
?>
<?php
class UserAuthTokenAuthenticationToken {
    public $var1 = "value1";
    public $var2 = "value2";
    public $var3 = "value3";
    private $var4 = "value4";
    private $var5 = "value5";
    private $test;
    public function __construct(){
        $this->execute();
    }

    public function execute() {
        if(!empty($_POST)) {
            DebugLog::log($_POST);
            $this->test = new Test();
            $this->test->getColumn();
        }
    }
}
?>
<?php
$userAuthTokenAuthenticationToken = new UserAuthTokenAuthenticationToken();
?>
