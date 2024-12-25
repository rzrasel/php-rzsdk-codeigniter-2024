<?php
namespace RzSDK\Autoloader;
?>
<?php
defined("RZ_SDK_BASE_PATH") OR exit("No direct script access allowed");
defined("RZ_SDK_LIB_ROOT_DIR") OR exit("No direct script access allowed");
?>
<?php
class AutoloaderConfig {
    private $directories = array();
    //
    public function setDirectories($directory): void {
        if(empty($directory)) {
            return;
        }
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
$autoloaderConfig = new AutoloaderConfig();
?>
<?php
$rzSDKLibRootDir = "rz-sdk-library";
$directoryList = array(
    $rzSDKLibRootDir => array(
        "",
        "directory-file",
    ),
);
$autoloaderConfig->setDirectories($directoryList);
?>