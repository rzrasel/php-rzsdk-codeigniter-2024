<?php
namespace RzSDK\Autoloader;
?>
<?php
class AutoloaderConfig {
    private static ?AutoloaderConfig $instance = null;
    private $directories = array();
    private $cacheFilePath = "";
    private $cacheDirName = "cache-file-path";
    private $cacheFileName = "cache-path-data.json";
    private $isFileWrite = false;
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

    public function setDirectories($directory) {
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
        return $this;
    }
    public function getDirectories(): array {
        return $this->directories;
    }

    public function setIsFileWrite(bool $isFileWrite = false) {
        $this->isFileWrite = $isFileWrite;
        //echo "called";
        return $this;
    }

    public function getIsFileWrite(): bool {
        return $this->isFileWrite;
    }

    public function setCacheFilePath(string $cacheFilePath) {
        $this->cacheFilePath = $cacheFilePath;
        return $this;
    }

    public function getCacheFilePath(): string {
        return $this->cacheFilePath;
    }

    public function setCacheFileName(string $cacheFileName) {
        $this->cacheFileName = $cacheFileName;
        return $this;
    }

    public function getCacheFileName(): string {
        return $this->cacheFileName;
    }

    public function setCacheDirName(string $cacheDirName) {
        $this->cacheDirName = $cacheDirName;
        return $this;
    }

    public function getCacheDirName(): string {
        return $this->cacheDirName;
    }

    public function getCacheDirWritePath() {
        $cacheFilePath = $this->cacheFilePath . DIRECTORY_SEPARATOR . $this->cacheDirName;
        $cacheFilePath = trim(trim($cacheFilePath, "\\"), "/");
        return $cacheFilePath;
    }

    public function getCacheFileWritePath() {
        $cacheFilePath = $this->getCacheDirWritePath() . DIRECTORY_SEPARATOR . $this->cacheFileName;
        $cacheFilePath = trim(trim($cacheFilePath, "\\"), "/");
        return $cacheFilePath;
    }
}
?>
<?php
$directoryList = array("");
global $autoloaderConfig;
$autoloaderConfig = AutoloaderConfig::getInstance();
$autoloaderConfig->setDirectories($directoryList);
?>