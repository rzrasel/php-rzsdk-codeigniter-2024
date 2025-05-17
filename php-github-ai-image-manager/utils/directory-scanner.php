<?php
namespace App\Microservice\Utils\Directory\Scanner;
?>
<?php
class DirectoryScanner {
    /**
     * Scan a directory recursively with optional file extension filter.
     *
     * @param string|null $dir        Directory path to scan
     * @param int         $level      Directory depth level
     * @param string|array $extensions Single extension like "php" or array of extensions ["php", "jpg"]
     * @return array
     */
    public static function scanDirectoryFiles($dir = null, $level = 0, $extensions = []) {
        if ($dir === null || !is_dir($dir)) {
            return [];
        }

        // Normalize extensions to lowercase array
        if (!is_array($extensions)) {
            $extensions = !empty($extensions) ? [strtolower($extensions)] : [];
        } else {
            $extensions = array_map('strtolower', $extensions);
        }
        //echo "<pre>" . print_r($extensions, true) . "</pre>";

        $structure = [
            "level" => $level,
            "files" => []
        ];

        $items = scandir($dir);

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $item;

            if (is_file($path)) {
                $fileExt = strtolower(pathinfo($item, PATHINFO_EXTENSION));
                if (empty($extensions) || in_array($fileExt, $extensions)) {
                    $structure['files'][] = $item;
                }
            } elseif (is_dir($path)) {
                $structure[$item] = self::scanDirectoryFiles($path, $level + 1, $extensions);
            }
        }

        return $structure;
    }
}
?>