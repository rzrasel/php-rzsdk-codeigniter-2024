<?php
namespace RzSDK\Autoloader;
?>
<?php
class AutoloaderConfig {
    private static ?AutoloaderConfig $instance = null;
    private $directories = array();
    private function __construct() {
        /*echo "<br />";
        echo __CLASS__ . " " . __METHOD__ . PHP_EOL;*/
    }

    public static function getInstance(): AutoloaderConfig {
        if (self::$instance === null || !isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setDirectories($directory): void {
        /*if(empty($directory)) {
            return;
        }*/
        if(is_string($directory)) {
            /* if(!array_key_exists($directory, $this->directories)) {
                $this->directories[] = $directory;
            } */
            if(!in_array($directory, $this->directories)) {
                $this->directories[] = $directory;
            }
        } else if(is_array($directory)) {
            foreach($directory as $key => $value) {
                /* echo $key;
                echo "<br />"; */
                if(is_string($key)) {
                    /* if(!array_key_exists($key, $this->directories)) {
                        $this->directories[] = $key;
                    } */
                    if(!in_array($key, $this->directories)) {
                        $this->directories[$key] = $value;
                    }
                } else {
                    if(!in_array($value, $this->directories)) {
                        $this->directories[] = $value;
                    }
                }
            }
        }
    }
    public function getDirectories(): array {
        return $this->directories;
    }
}
?>
<?php
$directoryList = array("");
global $autoloaderConfig;
$autoloaderConfig = AutoloaderConfig::getInstance();
$autoloaderConfig->setDirectories($directoryList);
?>