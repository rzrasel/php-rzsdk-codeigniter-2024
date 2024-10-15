<?php
use RzSDK\Utils\ObjectPropertyWizard;
use RzSDK\Log\DebugLog;
?>
<?php
class Test {
    public $var1 = "value1";
    public $var2 = "value2";
    public $var3 = "value3";
    private $var4 = "value4";
    private $var5 = "value5";
    protected $var6 = "value6";

    public function getColumn() {
        $testOne = ObjectPropertyWizard::getProtectedVariableKeys($this);
        DebugLog::log($testOne);
        $this->var1 = "sljfsjfk lfk";
        $this->var1 = ObjectPropertyWizard::getVariableName();
        DebugLog::log($this->var1);
    }
}
?>
<?php

/*function getVariableName() {
    $trace = debug_backtrace();
    //DebugLog::log($trace);
    $line = file($trace[0]["file"])[$trace[0]["line"] - 1];
    $line = trim(str_replace("\$this->", "$", $line));
    //DebugLog::log($line);
    //echo $line;
    preg_match('/\\$(\w+)/', $line, $matches);
    //DebugLog::log($matches);
    return $matches[1];
}

$myVar = "Hello, World!";
$varName = getVariableName();
echo $varName . PHP_EOL; // Output: myVar*/


/*// Declare and initialize a variable
$test = "This is a string";

// Function that returns the variable name
function getVariavleName($var) {
    foreach($GLOBALS as $varName => $value) {
        DebugLog::log($value);
        if ($value === $var) {
            return $varName;
        }
    }
    return null;
}

// Function call and display the
// variable name
print getVariavleName($test);*/
