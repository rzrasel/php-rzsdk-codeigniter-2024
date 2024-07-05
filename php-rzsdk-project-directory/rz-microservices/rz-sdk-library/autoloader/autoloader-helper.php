<?php
namespace RzSDK\Autoloader;
?>
<?php
defined("RZ_SDK_BASEPATH") OR exit("No direct script access allowed");
defined("RZ_SDK_WRAPPER") OR exit("No direct script access allowed");
?>
<?php
class AutoloaderHelper {
    public function toNamespace($class) {
        $classParts = explode("\\", $class);
        return end($classParts);
    }

    public function fromCamelCase($text, $replaceBy = "-") {
        $retVal = preg_replace("/([A-Z])/", $replaceBy . "$1", $text);
        return trim($retVal, $replaceBy);
    }

    public function toLower($text) {
        return strtolower($text);
    }

    protected function getDirectoryToPath($directory, $directories = array(), &$results = array()) {
        $directory = rtrim($directory, "/");
        if(is_array($directories)) {
            //$this->log($directories);
            $results = array();
            foreach($directories as $key => $value) {
                if(is_array($value)) {
                    //echo "{$key} - {$value} from if";
                    $path = empty($directory) ? "{$key}/" : "{$directory}/{$key}/";
                    $retVal = $this->getDirectoryToPath($path, $value);
                    $results[] = $path;
                    //$directories[] = $path;
                    foreach($retVal as $dir) {
                        $dir = rtrim($dir, "/");
                        $results[] = $dir;
                    }
                    //$this->log($retVal);
                } else {
                    $path = empty($directory) ? "{$value}/" : "{$directory}/{$value}/";
                    $path = rtrim($path, "/");
                    $results[] = $path;
                }
            }
        } else {
            $path = empty($directory) ? "{$directories}/" : "{$directory}/{$directories}/";
            $path = rtrim($path, "/");
            $results[] = $path;
        }
        $directories = $results;
        return $directories;
    }

    public function getExistedFilePath($directories, $file) {
        // With $extension = ".php"
        if(is_array($directories)) {
            echo "<br /><br />";
            foreach($directories as $directory) {
                $directory = rtrim($directory, "/") . "/" . $file;
                echo "{$directory}<br />";
                if($this->isFileExists($directory)) {
                    //echo "exist {$directory}<br />";
                    return $directory;
                }
            }
            echo "<br /><br />";
        } else {
            $directory = rtrim($directories, "/") . "/" . $file;
            if(empty($directories)) {
                $directory = $file;
            }
            if($this->isFileExists($directory)) {
                return $directory;
            }
        }
        return null;
    }

    public function isFileExists($path) {
        //echo "<br />================ {$path}<br />";
        //if(file_exists($path) && is_readable($path)) {
        if(file_exists($path)) {
            return true;
        }
        return false;
    }
}
?>