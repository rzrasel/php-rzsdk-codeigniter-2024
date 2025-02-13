<?php
require_once("find-working-directory.php");
require_once("autoloader-config.php");
require_once("directory-scanner.php");
require_once("autoloader.php");
?>
<?php
$startPath = __DIR__;
$targetFolder = "app-sql-database-schema";
$relativePath = "../";
$result = FindWorkingDirectory::findTopLevelDirectory($startPath, $targetFolder, $relativePath);
if($result) {
    $realPath = $result["realpath"];
    $relativePath = $result["relativepath"];
    $relativePath = dirname($relativePath);
    /*echo $relativePath;
    $directoryScanner = new DirectoryScanner();
    $results = $directoryScanner->scanDirectory($relativePath);
    echo "<pre>" . print_r($results, true) . "</pre>";*/
    global $autoloaderConfig;
    $autoloaderConfig->setDirectories("");
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
