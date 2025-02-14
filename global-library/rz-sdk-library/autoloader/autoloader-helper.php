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
    public function getClassToFilePath($className, $directories, AutoloaderConfig $autoloaderConfig, $fileExtension = ".php") {
        $dirList = [];
        foreach($directories as $directory) {
            $directoryScanner = new DirectoryScanner();
            $results = $directoryScanner->scanDirectory($directory);
            $dirList = array_merge($dirList, $results);
        }
        //echo "<pre>" . print_r($dirList, true) . "</pre>";
        //echo "isFileWrite: {$isFileWrite}";
        $fileName = $this->getFileList($className, $dirList, $fileExtension);
        if($fileName && $autoloaderConfig->getIsFileWrite()) {
            $this->writeCachePath($autoloaderConfig, $fileName, $className);
        }
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
                $fileName = $item . $fileExtension;
                $directory = trim(trim($directory, "\\"), "/");
                $fileName = $directory . "/" . $fileName;
                $fileName = trim(trim($fileName, "\\"), "/");
                /*echo "<br />";
                echo $fileName;
                echo "<br />";*/
                if(file_exists($fileName)) {
                    /*echo "Exists: $fileName";
                    echo "<br />";*/
                    return $fileName;
                }
            }
            $fileList[] = $fileName;
            /*echo "<br />";
            echo $fileName;
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

    public function writeCachePath(AutoloaderConfig $autoloaderConfig, $classFilePath, $className) {
        $fileWriteDirPath = $autoloaderConfig->getCacheDirWritePath();
        if(!is_dir($fileWriteDirPath) || !is_writable($fileWriteDirPath) || !file_exists($fileWriteDirPath)) {
            $permissions = 0777; // Full read, write, and execute permissions
            $permissions = 0770; // Full read, write, and execute permissions
            mkdir($fileWriteDirPath, $permissions, true);
            chmod("$fileWriteDirPath", $permissions);
        }
        $jsonFilePath = $autoloaderConfig->getCacheFileWritePath();
        $fileData = array();
        $fileData = $this->read($jsonFilePath);
        if(empty($fileData)) {
            $fileData = array();
        } else {
            $fileData = json_decode($fileData, true);
            $fileData[$classFilePath] = $className;
        }
        /*echo "<br />";
        echo "<br />";
        echo "<pre>" . print_r($fileData, true) . "</pre>";
        echo "<br />";
        echo "<br />";*/
        // Beautify the JSON
        $prettyJson = json_encode($fileData, JSON_PRETTY_PRINT);
        $this->write($jsonFilePath, $prettyJson);
    }
    public function readCachePath(AutoloaderConfig $autoloaderConfig, $className) {
        $jsonFilePath = $autoloaderConfig->getCacheFileWritePath();
        $fileData = $this->read($jsonFilePath);
        if(!empty($fileData)) {
            $fileData = json_decode($fileData, true);
            $findArrayKeyByValue = new FindArrayKeyByValue();
            $results = $findArrayKeyByValue->findByValueExactMatch($fileData, $className);
            /*echo "<br />";
            echo "<br />";
            echo "<pre>" . print_r($results, true) . "</pre>";
            echo "<br />";
            echo "<br />";*/
            if(!$results) {
                return false;
            }
            if(!is_array($results)) {
                return false;
            }
            $isFileExists = false;
            foreach($results as $item) {
                if(file_exists($item)) {
                    $isFileExists = true;
                    require_once($item);
                }
            }
            return $isFileExists;
        }
        return false;
    }

    public function write($filePath, $fileData) {
        /*$filePointer = fopen($filePath, "w");
        fwrite($filePointer, $fileData);
        fclose($filePointer);*/
        // Write the beautified JSON to the file
        file_put_contents($filePath, $fileData);
    }

    public function read($filePath) {
        if(!file_exists($filePath)) {
            return null;
        }
        return file_get_contents($filePath);
    }

    public function isFileExistsOld($path) {
        if(file_exists($path)) {
            return true;
        }
        return false;
    }
}
?>