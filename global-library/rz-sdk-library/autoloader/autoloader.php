<?php
namespace RzSDK\Autoloader;
?>
<?php
require_once("autoloader-helper.php");
require_once("autoloader-config.php");
require_once("directory-scanner.php");
require_once("convert-case-to-file-name.php");
?>
<?php
class Autoloader extends AutoloaderHelper{
    private $directories;
    public function __construct(AutoloaderConfig $directories = new AutoloaderConfig()) {
        $directories = $directories->getDirectories();
        $this->directories = is_array($directories) ? $directories : [$directories];
        //$this->scanDirectories();
        spl_autoload_register(array($this, "autoloadRegister"));
    }
    public function scanDirectories() {
        $dirList = [];
        foreach($this->directories as $directory) {
            $directoryScanner = new DirectoryScanner();
            $results = $directoryScanner->scanDirectory($directory);
            $dirList = array_merge($dirList, $results);
        }
    }
    private function autoloadRegister($className) {
        $class = parent::toNamespace($className);
        //$class = parent::toLower(parent::fromCamelCase($class));
        //$fileName = $class . ".php";
        /*$data = (new ConvertCaseToFileName())->toFileName($class);
        echo "<pre>" . print_r($data, true) . "</pre>";*/
        $fileName = parent::getClassToFilePath($class, $this->directories);
        if($fileName) {
            //echo $fileName;
            require_once($fileName);
        }
    }
    public function directoryFile($directories) {}
}
?>
<?php
global $autoloaderConfig;
$autoloader = new Autoloader($autoloaderConfig);
//echo $autoloader->existedFilePath;
?>
