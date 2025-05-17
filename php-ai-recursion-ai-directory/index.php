<?php
require_once("utils/directory-scanner.php");
require_once("utils/extract-path-util.php");
require_once("utils/file-content-writer.php");
?>
<?php
use App\Microservice\AI\Directory\Scanner\DirectoryScanner;
use App\Microservice\AI\Directory\Scanner\Util\ExtractPathUtil;
?>
<?php
$dirPath = "target-directory";
$allFiles = [];
$isCheckBox = true;
$writeSuccess = false;
$selectedFiles = [];
$scannedFiles = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formName = $_POST['post_form_name'] ?? '';

    if ($formName === "scan_directory") {
        $isCheckBox = isset($_POST['show_checkboxes']);
        $allFiles = DirectoryScanner::scanDirectoryFiles($dirPath);
        $selectedFiles = $allFiles;
    }

    if ($formName === "content_writing") {
        $selectedFiles = $_POST['selected_files'] ?? [];
        $scannedFiles = $_POST['scanned_files'] ?? [];
        $isCheckBox = $_POST['show_checkboxes'];
        $allFiles = $scannedFiles;
        $contentFiles = $scannedFiles;
        if($isCheckBox) {
            $contentFiles = $selectedFiles;
        }

        if (!empty($contentFiles)) {
            $writeSuccess = FileContentWriter::saveToFile("all-files-data.txt", $contentFiles, $dirPath);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Directory Scanner</title>
    <link rel="stylesheet" href="assets/style/style.css">
</head>
<body>
<h2>Directory Scanner</h2>

<!-- Scan Form -->
<form method="post">
    <label>
        <input type="checkbox" name="show_checkboxes" <?= $isCheckBox ? 'checked' : '' ?>>
        Show checkboxes
    </label>
    <input type="hidden" name="post_form_name" value="scan_directory">
    <button type="submit" name="btn_scan_directory">Scan Directory</button>
</form>

<?php if (!empty($allFiles)): ?>
    <h3>Directory Structure</h3>

    <?php if ($isCheckBox || (!$isCheckBox && !empty($allFiles))): ?>
        <form method="post">
            <?= DirectoryScanner::makeDirectoryStructure($allFiles, $isCheckBox, $selectedFiles); ?>
            <br>
            <input type="hidden" name="show_checkboxes" value="<?= $isCheckBox ? '1' : '' ?>">
            <input type="hidden" name="post_form_name" value="content_writing">
            <?php foreach ($allFiles as $file): ?>
                <input type="hidden" name="scanned_files[]" value="<?= htmlspecialchars($file); ?>">
            <?php endforeach; ?>
            <button type="submit" name="btn_content_writing">Write Content to File</button>
        </form>
    <?php endif; ?>
<?php endif; ?>

<?php if ($writeSuccess): ?>
    <p style="color: green;"><strong>File content successfully written to <code>all-files-data.txt</code>.</strong></p>
<?php elseif (isset($_POST['post_form_name']) && $_POST['post_form_name'] === "content_writing" && empty($selectedFiles)): ?>
    <p style="color: red;"><strong>No files selected or write failed.</strong></p>
<?php endif; ?>

<script src="assets/js/script.js"></script>
</body>
</html>