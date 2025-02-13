<?php
require_once("find-working-directory.php");
require_once("path-type.php");
?>
<?php
use RzSDK\Include\Import\FindWorkingDirectory;
use RzSDK\Include\Import\PathType;
?>

<?php
$startPath = __DIR__;
$targetFolder = "global-library/rz-sdk-library";
$relativePath = "../";
$results = FindWorkingDirectory::findTopLevelDirectory($startPath, $targetFolder, $relativePath);
echo "<pre>" . print_r($results, true) . "</pre>";
if($results) {
    $realPath = $results["realpath"];
    $relativePath = $results["relativepath"];
    $relativePath = dirname($relativePath);
    /*echo $relativePath;
    $directoryScanner = new DirectoryScanner();
    $results = $directoryScanner->scanDirectory($relativePath);
    echo "<pre>" . print_r($results, true) . "</pre>";*/
    global $autoloaderConfig;
    $autoloaderConfig->setDirectories($realPath);
    /*$autoloaderConfig->setDirectories("../");
    $autoloaderConfig->setDirectories("../../");*/
    //$autoloaderConfig->setDirectories("../../../");
    $results = $autoloaderConfig->getDirectories();
    //$autoloader = new Autoloader($autoloaderConfig);
    new findTest_class();
    //new FindTestClass();
} else {
    echo "Directory not found.\n";
}
?>
<?php
/*class FindTestClass {
}*/
?>
