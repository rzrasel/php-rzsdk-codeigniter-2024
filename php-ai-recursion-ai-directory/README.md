<?php
namespace App\Microservice\Directory\Scanner\Module;
?>
<?php
trait ScanDirectoryFiles {
    public static function scanDirectoryFiles($dir = null, $base_dir = null, $level = 0) {
        if (empty($dir)) {
            $dir = __DIR__;
        }

        if ($base_dir === null) {
            $base_dir = realpath($dir);
        }

        $files = array();

        if (!is_dir($dir)) {
            return $files;
        }

        $items = scandir($dir);

        foreach ($items as $item) {
            if ($item === "." || $item === "..") {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $item;

            if (is_file($path)) {
                $realPath = realpath($path);
                $relativePath = ltrim(str_replace($base_dir, '', $realPath), DIRECTORY_SEPARATOR);
                $files[] = [
                    "level" => $level,
                    "path" => $relativePath
                ];
            } elseif (is_dir($path)) {
                $files = array_merge($files, self::scanDirectoryFiles($path, $base_dir, $level + 1));
            }
        }

        return $files;
    }
}
?>
<?php
class DirectoryScanner {
    use ScanDirectoryFiles;
}
?>
<?php
$responseFileList = DirectoryScanner::scanDirectoryFiles($dirPath);
//echo "<pre>" . print_r($allFiles, true) . "</pre> " . __LINE__;
$allFiles = array();
foreach ($responseFileList as $responseFile) {
    if(is_array($responseFile)) {
        $allFiles[] = $responseFile["path"];
    }
    $allFiles[] = $responseFile["path"];
}
?>

- while $responseFile is array looping like recursive