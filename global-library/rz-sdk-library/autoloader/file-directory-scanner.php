<?php
namespace RzSDK\Autoloader;
?>
<?php
class FileDirectoryScanner {
    public function scanDirectory($dir) {
        $fileList = [];

        // Check if the provided directory exists
        if (!is_dir($dir)) {
            return ["Error: Directory does not exist"];
        }

        // Scan the directory
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === "." || $item === "..") {
                continue;
            }

            $fullPath = $dir . DIRECTORY_SEPARATOR . $item;
            $fileList[] = $fullPath;

            // If it's a directory, scan it recursively
            if (is_dir($fullPath)) {
                $fileList = array_merge($fileList, $this->scanDirectory($fullPath));
            }
        }

        return $fileList;
    }
}
?>
<?php
// Example Usage
$scanner = new FileDirectoryScanner();
$allPaths = $scanner->scanDirectory("/path/to/your/directory");

echo print_r($allPaths, true);
/*
Want Only Directories (No Files)?
Modify the loop:

php
Copy
Edit
if (is_dir($fullPath)) {
    $fileList[] = $fullPath;
    $fileList = array_merge($fileList, $this->scanDirectory($fullPath));
}*/

?>