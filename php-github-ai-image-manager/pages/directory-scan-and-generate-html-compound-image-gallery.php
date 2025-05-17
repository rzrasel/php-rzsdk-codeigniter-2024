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
use App\Microservice\Utils\Directory\File\Rearrange\DirectoryFileRearrange;
?>
<?php
class DirectoryFileRemover {
    /**
     * Remove files with given extensions from the file list.
     *
     * @param array $files The directory array (like your $array).
     * @param array|string $extensions Extensions to remove (case-insensitive).
     * @return array Filtered array with files removed by extension.
     */
    public static function traverseDirectoryRemove($files, $extensions = []) {
        // Normalize $extensions to lowercase array
        if (!is_array($extensions)) {
            $extensions = !empty($extensions) ? [strtolower($extensions)] : [];
        } else {
            $extensions = array_map('strtolower', $extensions);
        }

        // Result array
        $filtered = [];

        // Traverse each directory label and file list
        foreach ($files as $item) {
            $label = $item['label'];
            $fileList = $item['files'];

            // Filter files that do NOT have the extensions to remove
            $filteredFiles = array_filter($fileList, function($file) use ($extensions) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                // Keep file if its extension is NOT in $extensions
                return !in_array($ext, $extensions);
            });

            // Add to result if files remain
            $filtered[] = [
                'label' => $label,
                'files' => array_values($filteredFiles), // reindex array
            ];
        }

        return $filtered;
    }
}
?>
<?php
class DirectorySingleFile {
    public static function traverseDirectorySingleFile($files, $extensions = []) {
        // Normalize extensions to lowercase array
        if (!is_array($extensions)) {
            $extensions = !empty($extensions) ? [strtolower($extensions)] : [];
        } else {
            $extensions = array_map('strtolower', $extensions);
        }

        $result = [];

        foreach ($files as $item) {
            $label = $item['label'];
            $fileList = $item['files'];
            $matchedFile = null;

            foreach ($fileList as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (empty($extensions) || in_array($ext, $extensions)) {
                    $matchedFile = $file;
                    break; // Stop after first match
                }
            }

            if ($matchedFile !== null) {
                $result[] = [
                    'label' => $label,
                    'count' => count($fileList),
                    'files' => [$matchedFile],
                ];
            }
        }

        return $result;
    }
}
?>
<?php
class DirectoryFileGallery {
    public static function traverseAndGenerateHtmlGallery($basePath, $files, $extensions = []) {
        if (!is_array($extensions)) {
            $extensions = !empty($extensions) ? [strtolower($extensions)] : [];
        } else {
            $extensions = array_map('strtolower', $extensions);
        }

        $html = '<div class="gallery-container" style="display:flex;flex-wrap:wrap;gap:20px;">';

        foreach ($files as $item) {
            $label = htmlspecialchars($item['label']);
            $count = intval($item['count']);
            $fileList = $item['files'];

            if (empty($fileList)) continue;

            $coverImage = $fileList[0];
            $ext = strtolower(pathinfo($coverImage, PATHINFO_EXTENSION));

            if (!empty($extensions) && !in_array($ext, $extensions)) {
                continue;
            }

            $imagePath = rtrim($basePath, '/') . '/' . $coverImage;
            $title = htmlspecialchars($label);
            $title = str_replace(['-', '_'], ' ', $title);
            $title = ucwords(strtolower(trim($title)));

            $html .= '
                <a href="' . $label . '.html" class="album" style="text-decoration:none;color:inherit;width:200px;">
                    <div style="border:1px solid #ccc;border-radius:8px;overflow:hidden;">
                        <img src="' . htmlspecialchars($imagePath) . '" alt="' . $label . '" style="width:100%;height:150px;object-fit:cover;">
                        <div style="padding:10px;text-align:center;">
                            <strong>' . $title . '</strong><br>
                            <span>' . $count . ' item' . ($count > 1 ? 's' : '') . '</span>
                        </div>
                    </div>
                </a>
            ';
        }

        $html .= '</div>';
        return $html;
    }
}
?>
<?php
function runWriteHtmlFile($fileData) {
    $fileData = "<link rel=\"stylesheet\" href=\"assets/style/style.css\">" . $fileData;
    //echo $fileData;
    // Generate filename: index-YYYY-MM-DD-His[random4].html
    $timestamp = date("Y-m-d-His");
    $random4 = str_pad(mt_rand(0, 9999), 4, "0", STR_PAD_LEFT);
    $filename = "index-{$timestamp}-{$random4}.html";
    $filename = "../file-write/compound-image-gallery.html";
    file_put_contents($filename, $fileData);
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
        $filteredArray = DirectoryFileRemover::traverseDirectoryRemove($directoryStructure, $readingFileExtension);
        //echo "<pre>" . print_r($filteredArray, true) . "</pre>";
        $topSingleFile = DirectorySingleFile::traverseDirectorySingleFile($filteredArray, $acceptedFileExtension);
        //echo "<pre>" . print_r($topSingleFile, true) . "</pre>";
        //$rearrangedData = DirectoryFileRearrange::traverseDirectoryRearrange($directoryStructure, $acceptedFileExtension, $readingFileExtension);
        //echo "<pre>" . print_r($rearrangedData, true) . "</pre>";
        //$fileData = DirectoryFileReader::traverseDirectoryRead($targetDirPath, $topSingleFile, $acceptedFileExtension, $readingFileExtension);
        /*$newTargetDirPath = str_replace("../", "", $targetDirPath);
        $fileData = str_replace($targetDirPath, $newTargetDirPath, $fileData);*/
        $galleryHtml = DirectoryFileGallery::traverseAndGenerateHtmlGallery($targetDirPath, $topSingleFile, $acceptedFileExtension);
        runWriteHtmlFile($galleryHtml);
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
                <a href="" class="left-side-menu-link">Scan And HTML Generate Full Image Gallery</a>
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
            <h2>Directory Scan and Generate HTML Compound Image Gallery</h2>
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