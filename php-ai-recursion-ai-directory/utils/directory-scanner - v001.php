<?php
class DirectoryScanner {
    public static function scanDirectoryFiles($dir, $is_real_path = false, $base_dir = null) {
        if (empty($dir)) {
            $dir = __DIR__;
        }

        if ($base_dir === null) {
            $base_dir = realpath($dir);
        }

        $files = [];

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
                if ($is_real_path) {
                    $files[] = $realPath;
                } else {
                    $relativePath = ltrim(str_replace($base_dir, '', $realPath), DIRECTORY_SEPARATOR);
                    $files[] = $relativePath;
                }
            } elseif (is_dir($path)) {
                $files = array_merge($files, self::scanDirectoryFiles($path, $is_real_path, $base_dir));
            }
        }

        return $files;
    }

    public static function makeDirectoryStructure(array $fileList) {
        $tree = [];

        foreach ($fileList as $filePath) {
            $parts = explode(DIRECTORY_SEPARATOR, $filePath);
            $current = &$tree;

            foreach ($parts as $part) {
                if (!isset($current[$part])) {
                    $current[$part] = [];
                }
                $current = &$current[$part];
            }
        }

        return self::renderDirectoryTree($tree);
    }

    private static function renderDirectoryTree(array $tree, $prefix = '') {
        $output = '';
        $total = count($tree);
        $index = 0;

        foreach ($tree as $name => $subtree) {
            $index++;
            $connector = ($index === $total) ? '└── ' : '├── ';
            $output .= $prefix . $connector . $name . "\n";

            if (is_array($subtree) && !empty($subtree)) {
                $nextPrefix = $prefix . ($index === $total ? '    ' : '│   ');
                $output .= self::renderDirectoryTree($subtree, $nextPrefix);
            }
        }

        return $output;
    }
}
?>