<?php
namespace RzSDK\Autoloader;
?>
<?php
defined("RZ_SDK_BASE_PATH") or exit("No direct script access allowed");
defined("RZ_SDK_LIB_ROOT_DIR") or exit("No direct script access allowed");
?>
<?php
class AutoloaderHelper {
    public function toNamespace($class)
    {
        $classParts = explode("\\", $class);
        return end($classParts);
    }

    public function fromCamelCase($text, $replaceBy = "-")
    {
        $retVal = preg_replace("/([A-Z])/", $replaceBy . "$1", $text);
        return trim($retVal, $replaceBy);
    }
    public function getClassToFilePath($className, $directories, $fileExtension = ".php") {
        $dirList = [];
        foreach($directories as $directory) {
            $directoryScanner = new DirectoryScanner();
            $results = $directoryScanner->scanDirectory($directory);
            $dirList = array_merge($dirList, $results);
        }
        //echo "<pre>" . print_r($dirList, true) . "</pre>";
        $fileName = $this->getFileList($className, $dirList, $fileExtension);
        return $fileName;
    }
    public function getFileList($className, $directories, $fileExtension = ".php") {
        $fileList = [];
        $classToFileNameList = (new ConvertCaseToFileName())->toFileName($className);
        //echo "<pre>" . print_r($classToFileNameList, true) . "</pre>";
        //foreach($directories as $directory) {}
        foreach($classToFileNameList as $item) {
            $fileName = $item . $fileExtension;
            $fileList[] = $fileName;
            //echo realpath($fileName);
            if(file_exists($fileName)) {
                //echo "Exists: $fileName";
                return $fileName;
            }
            foreach($directories as $directory) {
                $directory = trim(trim($directory, "\\"), "/");
                $fileName = $directory . "/" . $fileName;
                $fileName = trim(trim($fileName, "\\"), "/");
            }
            $fileList[] = $fileName;
            /*echo $fileName;
            echo "<br />";*/
            if(file_exists($fileName)) {
                /*echo "Exists: $fileName";
                echo "<br />";*/
                return $fileName;
            }
        }
        //echo "<pre>" . print_r($fileList, true) . "</pre>";
        return false;
    }

    public function isFileExistsOld($path) {
        if(file_exists($path)) {
            return true;
        }
        return false;
    }
}
?>