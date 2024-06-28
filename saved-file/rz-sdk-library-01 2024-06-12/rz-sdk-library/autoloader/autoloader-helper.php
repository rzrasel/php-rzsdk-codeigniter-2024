<?php
namespace RzSDK\Autoloader;
defined("RZ_SDK_BASEPATH") OR exit("No direct script access allowed");
defined("RZ_SDK_WRAPPER") OR exit("No direct script access allowed");
?>
<?php
class AutoloaderHelper {
    private $fileNameList = array();
    private $existedFilePath = "";

    public function adaptFileName($class) {
        $class = $this->toNamespace($class);
        $this->fileNameList[] = $class;
        $this->fileNameList[] = $this->toLower($class);
        $this->fileNameList[] = $this->toCamel($class);
        $this->fileNameList[] = $this->toCamel($class, "_");

        $this->fileNameList[] = $this->toLower($this->toCamel($class));
        $this->fileNameList[] = $this->toLower($this->toCamel($class, "_"));
        return $this->fileNameList;
    }

    public function getExistedFile() {
        return $this->existedFilePath;
    }

    public function toLower($text) {
        return strtolower($text);
    }

    public function toCamel($text, $replaceBy = "-") {
        $retVal = preg_replace("/([A-Z])/", $replaceBy . "$1", $text);
        return trim($retVal, $replaceBy);
    }

    public function toNamespace($class) {
        $classParts = explode("\\", $class);
        return end($classParts);
    }

    public function prepareExistedFile($basePath, $dirtoryList, $fileNameList, $extension = ".php") {
        $filePathList = array();
        //$basePath = "";
        if(!empty($basePath)) {
            $basePath = $basePath. "/";
        }
        if(!empty($dirtoryList)) {
            foreach($dirtoryList as $dirtoryItem) {
                if(!empty($dirtoryItem)) {
                    $dirtoryItem = $dirtoryItem. "/";
                }
                foreach($fileNameList as $fileName) {
                    $filePathList[] = $basePath . $dirtoryItem . $fileName . $extension;
                }
            }
        } else {
            foreach($fileNameList as $fileName) {
                $filePathList[] = $basePath . $fileName . $extension;
            }
        }
        foreach($filePathList as $filePathItem) {
            if(file_exists($filePathItem) && is_readable($filePathItem)) {
                /* echo "<font color=\"green\">File exists:</font> <b><font color=\"#404079\">{$filePathItem}</font></b>";
                echo "<br />"; */
                $this->existedFilePath = $filePathItem;
                //return;
            } else {
                /* echo "<font color=\"red\">Error file not exsits:</font> <b><font color=\"#7676a7\">{$filePathItem}</font></b>";
                echo "<br />"; */
            }
        }
    }

    public function getFileNameList() {
        return $this->fileNameList;
    }

    /* public function runHelper() {
        //
    } */
}
?>