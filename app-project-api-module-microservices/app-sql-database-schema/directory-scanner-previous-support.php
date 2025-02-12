<?php
?>
<?php
class DirectoryScanner {
    public function scanDirectory($dir) {
        $dirList = [];
        $baseDir = realpath($dir ?: getcwd());

        if (!$baseDir || !is_dir($baseDir)) {
            return ["Error: Directory does not exist -> $dir"];
        }

        $prefix = ($dir === "") ? str_replace("\\", "/", getcwd()) . "/" : "";
        $this->scan($baseDir, $dir, $prefix, $dirList);
        return $dirList;
    }

    private function scan($path, $originalDir, $prefix, &$list) {
        foreach (scandir($path) as $item) {
            if ($item === "." || $item === "..") continue;
            $fullPath = str_replace("\\", "/", realpath("$path/$item"));
            if (is_dir($fullPath)) {
                $list[] = ($originalDir === "..") ? "../$item" : str_replace($prefix, "", $fullPath);
                $this->scan($fullPath, $originalDir, $prefix, $list);
            }
        }
    }
}
class DirectoryScannerOld1 {
    public function scanDirectory($dir) {
        $dirList = [];

        // Normalize path and get absolute path
        $dir = rtrim($dir, DIRECTORY_SEPARATOR);
        $baseDir = ($dir === "") ? getcwd() : realpath($dir); // Handle "" as current directory

        // Validate if directory exists
        if (!$baseDir || !is_dir($baseDir)) {
            return ["Error: Directory does not exist -> $dir"];
        }

        // Standardize path format for Windows/Linux
        $baseDir = str_replace("\\", "/", $baseDir);
        $currentDirPrefix = rtrim(str_replace("\\", "/", getcwd()), "/") . "/";

        // Recursive directory scan
        $this->scan($baseDir, $dir, $currentDirPrefix, $dirList);
        return $dirList;
    }

    private function scan($currentPath, $originalDir, $currentDirPrefix, &$dirList) {
        $items = scandir($currentPath);
        foreach ($items as $item) {
            if ($item === "." || $item === "..") {
                continue;
            }

            $fullPath = realpath($currentPath . DIRECTORY_SEPARATOR . $item);
            $fullPath = str_replace("\\", "/", $fullPath); // Normalize path

            if (is_dir($fullPath)) {
                // Determine correct relative path
                if ($originalDir === "..") {
                    $relativePath = "../" . $item;
                } elseif ($originalDir === "") {
                    $relativePath = str_replace($currentDirPrefix, "", $fullPath);
                } else {
                    $relativePath = $fullPath;
                }

                $dirList[] = $relativePath;
                $this->scan($fullPath, $originalDir, $currentDirPrefix, $dirList);
            }
        }
    }
}

// Example Usage
$scanner = new DirectoryScanner();

echo "<br />";
echo "<br />";
echo "Scanning '../':\n";
echo "<pre>" . print_r($scanner->scanDirectory(".."), true) . "</pre>"; // Scan from "../"

echo "<br />";
echo "<br />";
echo "\nScanning '':\n";
echo "<pre>" . print_r($scanner->scanDirectory(""), true) . "</pre>"; // Scan from current directory

echo "<br />";
echo "<br />";
echo "\nScanning full path:\n";
echo "<pre>" . print_r($scanner->scanDirectory(__DIR__), true) . "</pre>"; // Scan from full path
echo "<br />";
echo "<br />";
?>
<?php
class DirectoryScannerPreviousSupport {
    public function scanDirectory($dir) {
        $dirList = [];

        // Normalize the directory path
        $dir = rtrim($dir, DIRECTORY_SEPARATOR);

        // Check if the directory exists
        if (!is_dir($dir)) {
            return ["Error: Directory does not exist"];
        }

        // Scan directory
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === "." || $item === "..") {
                continue;
            }

            $fullPath = $dir . DIRECTORY_SEPARATOR . $item;

            // If it's a directory, add it and scan recursively
            if (is_dir($fullPath)) {
                $relativePath = ($dir === "..") ? ".." . DIRECTORY_SEPARATOR . $item : $fullPath;
                $dirList[] = $relativePath;
                $dirList = array_merge($dirList, $this->scanDirectory($fullPath));
            }
        }

        return $dirList;
    }
}

// Example Usage
/*$scanner = new DirectoryScannerPreviousSupport();
$allDirectories = $scanner->scanDirectory(".."); // Scan starting from ".."

echo "<pre>" . print_r($allDirectories, true) . "</pre>";*/
?>