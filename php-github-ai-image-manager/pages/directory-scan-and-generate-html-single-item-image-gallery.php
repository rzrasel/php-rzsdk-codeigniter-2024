<?php
$basePath = "../";
require_once("{$basePath}/utils/directory-scanner.php");
require_once("{$basePath}/utils/directory-traverse-retrieve.php");
require_once("{$basePath}/utils/directory-full-image-gallery.php");
require_once("{$basePath}/utils/directory-file-rename.php");
require_once("{$basePath}/utils/directory-file-rearrange.php");
?>
<?php
use App\Microservice\Utils\Directory\Scanner\DirectoryScanner;
use App\Microservice\Utils\Directory\Traverse\DirectoryTraverseRetrieve;
use App\Microservice\Utils\Directory\Image\Gallery\DirectoryFullImageGallery;
use App\Microservice\Utils\Directory\File\Rearrange\DirectoryFileRearrange;
?>
<?php
function runWriteHtmlFile($fileData, $fileName = "") {
    $fileName = str_replace(['_', ' '], '-', $fileName);
    $title = htmlspecialchars($fileName);
    $title = str_replace(['-', '_'], ' ', $title);
    $title = ucwords($title) . " - Image Gallery";
    //
    $sampleHtmlPath = "../file-write/index-sample.html";
    $sampleHtmlContent = file_get_contents($sampleHtmlPath);
    $sampleHtmlContent = str_replace("{sample-image-gallery-title-goes-here}", $title, $sampleHtmlContent);
    $sampleHtmlContent = str_replace("{sample-image-gallery-html-goes-here}", $fileData, $sampleHtmlContent);
    //echo $fileData;
    if(empty($fileName)) {
        // Generate filename: index-YYYY-MM-DD-His[random4].html
        $timestamp = date("Y-m-d-His");
        $random4 = str_pad(mt_rand(0, 9999), 4, "0", STR_PAD_LEFT);
        $filename = "index-{$timestamp}-{$random4}.html";
        $filename = "../file-write/item-image-gallery.html";
    } else {
        $filename = "../file-write/item-gallery-{$fileName}.html";
    }
    file_put_contents($filename, $sampleHtmlContent);
}
function runGenerateHtmlFile($requestData) {
    //echo "<pre>" . print_r($requestData) . "</pre>";
    $targetDirPath = $_POST["target_dir_path"];
    $acceptedFileExtension = $_POST["accepted_file_extension"];
    $readingFileExtension = $_POST["reading_file_extension"];
    if(!empty($_POST["btn_generate_html_file"])) {
        $targetDirPath = "../" . $targetDirPath;
        $scannedDirectory = DirectoryScanner::scanDirectoryFiles($targetDirPath, 0, $acceptedFileExtension);
        //echo "<pre>" . print_r($scannedDirectory, true) . "</pre>";
        $directoryStructure = DirectoryTraverseRetrieve::traverseDirectoryRetrieve($scannedDirectory, "root", '', 0, 1, $acceptedFileExtension);
        //echo "<pre>" . print_r($directoryStructure, true) . "</pre>";
        $rearrangedData = DirectoryFileRearrange::traverseDirectoryRearrange($directoryStructure, $acceptedFileExtension, $readingFileExtension);
        //echo "<pre>" . print_r($rearrangedData, true) . "</pre>";
        foreach ($rearrangedData as $fileItemData) {
            $fileName = $fileItemData["label"];
            //echo $fileItemData["label"];
            //echo "<pre>" . print_r($fileItemData, true) . "</pre>";
            $fileData = DirectoryFullImageGallery::traverseDirectoryRead($targetDirPath, $fileItemData, $acceptedFileExtension, $readingFileExtension);
            //echo $fileData;
            runWriteHtmlFile($fileData, $fileName);
        }
        //$fileData = DirectoryFullImageGallery::traverseDirectoryRead($targetDirPath, $rearrangedData, $acceptedFileExtension, $readingFileExtension);
        /*$newTargetDirPath = str_replace("../", "", $targetDirPath);
        $fileData = str_replace($targetDirPath, $newTargetDirPath, $fileData);*/
        //runWriteHtmlFile($fileData);
        return "HTML file generation completed.";
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
    $acceptedFileExtension = $_POST["accepted_file_extension"];
    if(!empty($acceptedFileExtension)){
        $acceptedFileExtension = preg_replace('/\s+/', '', $acceptedFileExtension);
        $acceptedFileExtension = explode(",", $acceptedFileExtension);
        $_POST["accepted_file_extension"] = $acceptedFileExtension;
    } else {
        $acceptedFileExtension = "";
    }
    $readingFileExtension = $_POST["reading_file_extension"];
    if(!empty($readingFileExtension)){
        $readingFileExtension = preg_replace('/\s+/', '', $readingFileExtension);
        $readingFileExtension = explode(",", $readingFileExtension);
        $_POST["reading_file_extension"] = $readingFileExtension;
    } else {
        $readingFileExtension = "";
    }
    $responseMessage = runGenerateHtmlFile($_POST);
    if(is_array($acceptedFileExtension)) {
        $acceptedFileExtension = implode(", ", $acceptedFileExtension);
    }
    if(is_array($readingFileExtension)) {
        $readingFileExtension = implode(", ", $readingFileExtension);
    }
} else {
    $responseMessage = "";
    $targetDirPath = "file-write/images";
    $acceptedFileExtension = array("png", "jpg", "md");
    $readingFileExtension = array("md");
    $acceptedFileExtension = implode(", ", $acceptedFileExtension);
    $readingFileExtension = implode(", ", $readingFileExtension);
    $filePrefix = "file-name";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Directory Scanner Html Generator Full Image Gallery</title>
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
                <a href="directory-scan-and-file-rename.php" class="left-side-menu-link">Scan And Rename</a>
            </li>
            <li class="left-side-menu-item">
                <a href="directory-scan-and-generate-html-full-image-gallery.php" class="left-side-menu-link">Scan And HTML Generate Full Image Gallery</a>
            </li>
            <li class="left-side-menu-item">
                <a href="directory-scan-and-generate-html-compound-image-gallery.php" class="left-side-menu-link">Scan And HTML Generate Compound Image Gallery</a>
            </li>
            <li class="left-side-menu-item">
                <a href="directory-scan-and-generate-html-single-item-image-gallery.php" class="left-side-menu-link">Scan And HTML Generate Single Item Image Gallery</a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="body-container">

        <!-- Page Header -->
        <header class="body-page-header">
            <h2>Directory Scan and Generate HTML Single Item Image Gallery</h2>
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
                    <label for="accepted_file_extension">Accepted File Extension (Comma Separated):</label>
                    <input type="text" id="accepted_file_extension" name="accepted_file_extension" value="<?= $acceptedFileExtension ?>" placeholder="Enter file extension (comma separated: png, jpg, jpeg)">
                </div>

                <!-- Prefix Input -->
                <div class="form-group">
                    <label for="reading_file_extension">Reading File Extension (Comma Separated):</label>
                    <input type="text" id="reading_file_extension" name="reading_file_extension" value="<?= $readingFileExtension ?>" placeholder="Enter file extension (comma separated: txt, md)">
                </div>

                <!-- Submit Button -->
                <div class="form-group">
                    <div class="submit-container">
                        <input type="submit" name="btn_generate_html_file" value="Submit" class="btn-submit">
                    </div>
                </div>

            </form>
        </section>
    </main>
</div>
</body>
</html>