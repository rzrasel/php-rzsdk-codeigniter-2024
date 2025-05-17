<?php
namespace App\Microservice\AI\Directory\Scanner\Module;
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
                $files[] = $relativePath;
            } elseif (is_dir($path)) {
                $files = array_merge($files, self::scanDirectoryFiles($path, $base_dir, $level + 1));
            }
        }

        return $files;
    }
}
?>