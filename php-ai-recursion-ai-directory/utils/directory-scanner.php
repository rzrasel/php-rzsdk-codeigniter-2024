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
                $relativePath = ltrim(str_replace($base_dir, '', $realPath), DIRECTORY_SEPARATOR);
                $files[] = $relativePath;
            } elseif (is_dir($path)) {
                $files = array_merge($files, self::scanDirectoryFiles($path, $is_real_path, $base_dir));
            }
        }

        return $files;
    }

    public static function makeDirectoryStructure(array $fileList, $isCheckBox = false, array $selectedFiles = []) {
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

        return self::renderDirectoryTree($tree, '', $isCheckBox, '', $selectedFiles);
    }

    private static function renderDirectoryTree(array $tree, $prefix = '', $isCheckBox = false, $path = '', array $selectedFiles = []) {
        $output = '';
        $total = count($tree);
        $index = 0;

        foreach ($tree as $name => $subtree) {
            $index++;
            $connector = ($index === $total) ? '└── ' : '├── ';
            $fullPath = ltrim($path . DIRECTORY_SEPARATOR . $name, DIRECTORY_SEPARATOR);
            $sanitizedClass = 'dir_' . md5($fullPath);

            $output .= '<div class="directory-item">';
            $output .= '<span class="directory-connector">' . $prefix . $connector . '</span>';

            if (is_array($subtree) && !empty($subtree)) {
                if ($isCheckBox) {
                    $output .= "<span class='toggle-folder' data-folder-icon='$sanitizedClass' onclick=\"toggleFolder('$sanitizedClass')\">▼</span> ";
                    $output .= "<span class='folder-name' data-folder='$sanitizedClass'><strong>" . htmlspecialchars($name) . "</strong></span>";
                } else {
                    $output .= "<strong>" . htmlspecialchars($name) . "</strong>";
                }

                $nextPrefix = $prefix . ($index === $total ? '&nbsp;&nbsp;&nbsp;' : '│&nbsp;&nbsp;&nbsp;');
                $output .= "</div><div class='folder-content open $sanitizedClass' id='$sanitizedClass'>";
                $output .= self::renderDirectoryTree($subtree, $nextPrefix, $isCheckBox, $fullPath, $selectedFiles);
                $output .= "</div>";
            } else {
                if ($isCheckBox) {
                    $checked = in_array($fullPath, $selectedFiles) ? 'checked' : '';
                    $strikeClass = $checked ? '' : ' strike';
                    $output .= "<label class='checkbox-label$strikeClass'><input type='checkbox' class='$sanitizedClass' name='selected_files[]' value='" . htmlspecialchars($fullPath) . "' $checked>" .
                        htmlspecialchars($name) . "</label>";
                    $output .= "<input type='hidden' name='scanned_files[]' value='" . htmlspecialchars($fullPath) . "'>";
                } else {
                    $output .= htmlspecialchars($name);
                }
                $output .= "</div>";
            }
        }

        return $output;
    }
}
?>