<?php
namespace RzSDK\Utils;
?>
<?php
use Exception;
use RzSDK\Log\DebugLog;
?>
<?php
class ObjectPropertyWizard {

    public static function getAllVariableWithKeyValue(object $object) {
        //$class = self::getClassName($object);
        $publicVariables = get_object_vars($object);
        //DebugLog::log($publicVariables);
        $keyValuePairs = array();
        foreach($publicVariables as $key => $value) {
            $key = trim($key);
            $keyValuePairs[$key] = $value;
        }
        $allVariables = get_mangled_object_vars($object);
        //DebugLog::log($allVariables);
        $privateVariables = array_diff_key($allVariables, $publicVariables);
        //DebugLog::log($privateVariables);
        $class = get_class($object);
        //DebugLog::log($class);
        foreach($privateVariables as $key => $value) {
            $key = trim($key);
            $pattern = "/^{$class}/si";
            if(preg_match($pattern, $key, $match)) {
                $key = substr($key, strlen($class), strlen($key));
            }
            if(substr($key, 0, 1) == "*") {
                $key = substr($key, 1);
            }
            $keyValuePairs[$key] = $value;
        }
        return $keyValuePairs;
    }

    public static function getAllVariableKeys(object $object) {
        return array_keys(self::getAllVariableWithKeyValue($object));
    }

    public static function getAllVariableValues(object $object) {
        return array_values(self::getAllVariableWithKeyValue($object));
    }

    public static function getPublicVariableWithKeyValue(object $object) {
        return get_object_vars($object);
    }

    public static function getPublicVariableKeys(object $object) {
        return array_keys(get_object_vars($object));
    }

    public static function getPublicVariableValues(object $object) {
        return array_values(get_object_vars($object));
    }

    public static function getPrivateVariableWithKeyValue(object $object) {
        $publicVariables = get_object_vars($object);
        $allVariables = get_mangled_object_vars($object);
        $privateVariables = array_diff_key($allVariables, $publicVariables);
        //DebugLog::log($privateVariables);
        $class = get_class($object);
        $keyValuePairs = array();
        foreach($privateVariables as $key => $value) {
            $key = trim($key);
            $pattern = "/^{$class}/si";
            if(preg_match($pattern, $key, $match)) {
                $key = substr($key, strlen($class), strlen($key));
            }
            if(substr($key, 0, 1) != "*") {
                $keyValuePairs[$key] = $value;
            }
        }
        return $keyValuePairs;
    }

    public static function getPrivateVariableKeys(object $object) {
        return array_keys(self::getPrivateVariableWithKeyValue($object));
    }

    public static function getPrivateVariableValues(object $object) {
        return array_values(self::getPrivateVariableWithKeyValue($object));
    }

    public static function getProtectedVariableWithKeyValue(object $object) {
        $publicVariables = get_object_vars($object);
        $allVariables = get_mangled_object_vars($object);
        $privateVariables = array_diff_key($allVariables, $publicVariables);
        $keyValuePairs = array();
        foreach($privateVariables as $key => $value) {
            $key = trim($key);
            if(substr($key, 0, 1) == "*") {
                $key = substr($key, 1);
                $keyValuePairs[$key] = $value;
            }
        }
        return $keyValuePairs;
    }

    public static function getProtectedVariableKeys(object $object) {
        return array_keys(self::getProtectedVariableWithKeyValue($object));
    }

    public static function getProtectedVariableValues(object $object) {
        return array_values(self::getProtectedVariableWithKeyValue($object));
    }

    public static function getVariableName() {
        $trace = debug_backtrace();
        //DebugLog::log($trace);
        $line = file($trace[0]["file"])[$trace[0]["line"] - 1];
        $line = trim($line);
        $line = explode("->", $line);
        //echo "The debug backtrace line: " . count($line);
        //DebugLog::log($line);
        $line = $line[count($line) - 1];
        //echo "The debug backtrace line: " . substr($line, 0, 1);
        if(substr($line, 0, 1) != "\$") {
            $line = "\$" . $line;
        }
        //echo "The debug backtrace line: {$line}";
        //$line = trim(str_replace("\$this->", "$", $line));
        //DebugLog::log($line);
        //echo $line;
        if(preg_match('/\\$(\w+)/', $line, $matches)) {
            //DebugLog::log($matches);
            return $matches[1];
        }
        return null;
    }

    private static function getClassName(object $object) {
        DebugLog::log(get_class($object));
        $e = new Exception();
        $trace = $e->getTrace();
        DebugLog::log($trace);
        $trace = debug_backtrace();
        $args = $trace[0]["args"];
        DebugLog::log($args);
        for($i = 0; $i < count($trace); $i++) {
            $class = $trace[$i]["class"];
            if (strpos($class, "\\") == false) {
                return $class;
            }
        }
        return null;
    }
}
?>
