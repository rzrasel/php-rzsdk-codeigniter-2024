<?php
$basePath = "../";
require_once("{$basePath}/utils/directory-scanner.php");
require_once("{$basePath}/utils/directory-traverse-retrieve.php");
require_once("{$basePath}/utils/directory-file-reader.php");
require_once("{$basePath}/utils/directory-file-rename.php");
require_once("{$basePath}/utils/directory-file-rearrange.php");
?>
<?php
use App\Microservice\Utils\Directory\Scanner\DirectoryScanner;
use App\Microservice\Utils\Directory\Traverse\DirectoryTraverseRetrieve;
use App\Microservice\Utils\Directory\File\Reader\DirectoryFileReader;
use App\Microservice\Utils\Directory\File\Rename\DirectoryFileRename;
use App\Microservice\Utils\Directory\File\Rearrange\DirectoryFileRearrange;
?>
<?php
/*$renameFileExtension = array(
    "png",
    "jpg",
    "jpeg",
);
$responseMessage = "";
$targetDirPath = "target-directory";
$renameFileExtension = array();
$filePrefix = "";*/
/*$dirPath = 'C:\xampp-portable-windows-x64-8.2.12-0-VS16\htdocs\php-rzsdk-codeigniter\php-ai-recursion-ai-directory\temp-project\target-directory';
$dirPath = "target-directory";
//$scanned = ['root' => DirectoryScanner::scanDirectoryFiles($dirPath)];
$filterExtension = "jpg";
$filterExtension = ['jpg', 'md'];
$filterExtension = "";
$scannedDirectory = DirectoryScanner::scanDirectoryFiles($dirPath, 0, $filterExtension);
//echo "<pre>" . print_r($scannedDirectory, true) . "</pre>";
$filterExtension = ['php', "png", "jpg", 'md'];
$isRenameFile = false;
if($isRenameFile) {
    $directoryStructurePlain = DirectoryTraverseRetrieve::traverseDirectoryRetrievePlain($scannedDirectory, '', $filterExtension);
    //echo "<pre>" . print_r($directoryStructurePlain, true) . "</pre>";
    DirectoryFileRename::traverseFileRename($dirPath, $directoryStructurePlain, "", ["png", "jpg"]);
    $scannedDirectory = DirectoryScanner::scanDirectoryFiles($dirPath, 0, $filterExtension);
}
$directoryStructure = DirectoryTraverseRetrieve::traverseDirectoryRetrieve($scannedDirectory, "root", '', 0, 2, $filterExtension);
//echo "<pre>" . print_r($directoryStructure, true) . "</pre>";
$exceptedExtension = ["png", "jpg", "md"];
$readExtension = ["md"];
$rearrangedData = DirectoryFileRearrange::traverseDirectoryRearrange($directoryStructure, $exceptedExtension, $readExtension);
//echo "<pre>" . print_r($rearrangedData, true) . "</pre>";
$fileData = DirectoryFileReader::traverseDirectoryRead($dirPath, $rearrangedData, $exceptedExtension, $readExtension);
$fileData = "<link rel=\"stylesheet\" href=\"assets/style/style.css\">" . $fileData;
//echo $fileData;
// Generate filename: index-YYYY-MM-DD-His[random4].html
$timestamp = date("Y-m-d-His");
$random4 = str_pad(mt_rand(0, 9999), 4, "0", STR_PAD_LEFT);
$filename = "index-{$timestamp}-{$random4}.html";
$filename = "index01.html";
file_put_contents($filename, $fileData);*/
function runRenameFileName($requestData) {
    //echo "<pre>" . print_r($requestData) . "</pre>";
    $targetDirPath = $_POST["target_dir_path"];
    $renameFileExtension = $_POST["file_extension"];
    $filePrefix = $_POST["file_prefix"];
    if(!empty($_POST["btn_rename_file"])) {
        $targetDirPath = "../" . $targetDirPath;
        $scannedDirectory = DirectoryScanner::scanDirectoryFiles($targetDirPath, 0, $renameFileExtension);
        //echo "<pre>" . print_r($scannedDirectory, true) . "</pre>";
        $directoryStructurePlain = DirectoryTraverseRetrieve::traverseDirectoryRetrievePlain($scannedDirectory, '', $renameFileExtension);
        //echo "<pre>" . print_r($directoryStructurePlain, true) . "</pre>";
        DirectoryFileRename::traverseFileRename($targetDirPath, $directoryStructurePlain, $filePrefix, $renameFileExtension);
        return "File rename completed.";
    }
    return "";
}
?>
<?php
if(!empty($_POST)){
    //echo "<pre>" . print_r($_POST) . "</pre>";
    foreach ($_POST as $key => $value) {
        $_POST[$key] = trim($value);
    }
    $responseMessage = "";
    $targetDirPath = $_POST["target_dir_path"];
    $renameFileExtension = $_POST["file_extension"];
    if(!empty($renameFileExtension)){
        $renameFileExtension = preg_replace('/\s+/', '', $renameFileExtension);
        $renameFileExtension = explode(",", $renameFileExtension);
        $_POST["file_extension"] = $renameFileExtension;
    } else {
        $renameFileExtension = "";
    }
    $filePrefix = $_POST["file_prefix"];
    $responseMessage = runRenameFileName($_POST);
    if(is_array($renameFileExtension)) {
        $renameFileExtension = implode(", ", $renameFileExtension);
    }
} else {
    $responseMessage = "";
    $targetDirPath = "target-directory";
    $renameFileExtension = array("png");
    $renameFileExtension = implode(", ", $renameFileExtension);
    $filePrefix = "file-name";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Directory Scanner Html Generator</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>
<body>
<div class="main-body-container">

    <!-- Sidebar Navigation -->
    <nav class="left-side-navbar">
        <ul class="left-side-menu">
            <li class="left-side-menu-item">
                <a href="../" class="left-side-menu-link">Home</a>
            </li>
            <li class="left-side-menu-item">
                <a href="" class="left-side-menu-link">Scan And Rename</a>
            </li>
            <li class="left-side-menu-item">
                <a href="directory-scan-and-generate-html-full-image-gallery.php" class="left-side-menu-link">Scan And HTML Generate Full Image Gallery</a>
            </li>
            <li class="left-side-menu-item">
                <a href="directory-scan-and-generate-html-compound-image-gallery.php" class="left-side-menu-link">Scan And HTML Generate Compound Image Gallery</a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="body-container">

        <!-- Page Header -->
        <header class="body-page-header">
            <h2>Directory Scan and Rename File</h2>
        </header>

        <!-- Page Content -->
        <section class="body-content">
            <form action="" method="POST" enctype="multipart/form-data" class="body-form">

                <!-- Message Display -->
                <?php if(!empty($responseMessage)) { ?>
                <div class="message">
                    <?= $responseMessage ?>
                </div>
                <?php } ?>

                <!-- Target Directory Path Input -->
                <div class="form-group">
                    <label for="target_dir_path">Target Directory Path:</label>
                    <input type="text" id="target_dir_path" name="target_dir_path" value="<?= $targetDirPath ?>" placeholder="Enter root target directory path">
                </div>

                <!-- Rename File Type Input -->
                <div class="form-group">
                    <label for="file_extension">File Extension (Comma Separated):</label>
                    <input type="text" id="file_extension" name="file_extension" value="<?= $renameFileExtension ?>" placeholder="Enter file extension (comma separated: png, jpg, jpeg)">
                </div>

                <!-- Prefix Input -->
                <div class="form-group">
                    <label for="file_prefix">File Prefix:</label>
                    <input type="text" id="file_prefix" name="file_prefix" value="<?= $filePrefix ?>" placeholder="Enter file prefix">
                </div>

                <!-- Submit Button -->
                <div class="form-group">
                    <div class="submit-container">
                        <input type="submit" name="btn_rename_file" value="Submit" class="btn-submit">
                    </div>
                </div>

            </form>
        </section>
    </main>
</div>
</body>
</html>