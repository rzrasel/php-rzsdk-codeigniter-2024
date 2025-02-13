<?php
class DirectoryScanner {
    public function scanDirectory($dir) {
        //echo "Starting scan for $dir<br />";
        $param = $dir;
        $find = "";
        $replace = "";
        if(str_starts_with($dir, ".")) {
            $driList = explode("/", $dir);
            if(empty($driList)) {
                $driList = explode("\\", $dir);
            }
            $dir = getcwd();
            $find = getcwd();
            foreach($driList as $item) {
                if(str_starts_with($item, ".")) {
                    $dir = dirname($dir);
                    $find = dirname($find);
                    $replace = $replace . DIRECTORY_SEPARATOR . $item;
                } else {
                    $dir = rtrim($dir, DIRECTORY_SEPARATOR);
                    $dir = $dir . DIRECTORY_SEPARATOR . $item;
                    $find = $find . DIRECTORY_SEPARATOR . $item;
                    $replace = $replace . DIRECTORY_SEPARATOR . $item;
                }
            }
        } else if(empty($dir)) {
            $dir = getcwd();
            $find = getcwd();
            $replace = "";
        }
        //echo "dir: " . $dir . "<br />find: " . $find . "<br />replace: " . $replace . "<br />";
        return $this->scanDirectoryRecursion($dir, $param, $find, $replace);
    }
    public function scanDirectoryRecursion($dir, $param = "", $search = "", $replace = "") {
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
                //$relativePath = ($dir === "..") ? ".." . DIRECTORY_SEPARATOR . $item : $fullPath;
                if(str_starts_with($param, ".") && !empty($param) && !empty($search) && !empty($replace)) {
                    $path = str_replace($search, $replace, $fullPath);
                    $dirList[] = str_replace("\\", "/", trim(trim($path, "\\"), "/"));
                } else if(empty($param) && !empty($search) && empty($replace)) {
                    $path = str_replace($search, $replace, $fullPath);
                    $dirList[] = str_replace("\\", "/", trim(trim($path, "\\"), "/"));
                } else {
                    $dirList[] = str_replace("\\", "/", "$fullPath");
                }
                $dirList = array_merge($dirList, $this->scanDirectoryRecursion($fullPath, $param, $search, $replace));
            }
        }

        return $dirList;
    }
}
?>
<?php
/*// Example Usage
$scanner = new DirectoryScanner();
echo "<pre>" . print_r($scanner->scanDirectory(""), true) . "</pre>";
echo "<pre>" . print_r($scanner->scanDirectory("../../global-library/rz-sdk-library"), true) . "</pre>";
echo "<pre>" . print_r($scanner->scanDirectory(__DIR__), true) . "</pre>";

echo "<br />";
echo "<br />";
echo getcwd();
echo "<br />";
echo basename(__DIR__);
echo "<br />";
echo "<br />";*/
?>