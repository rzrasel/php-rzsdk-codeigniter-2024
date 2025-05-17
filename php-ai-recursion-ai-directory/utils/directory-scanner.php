<?php
namespace App\Microservice\AI\Directory\Scanner;
?>
<?php
require_once("scanner-module/scan-directory-files.php");
?>
<?php
use App\Microservice\AI\Directory\Scanner\Module\ScanDirectoryFiles;
?>
<?php
class DirectoryScanner {
    use ScanDirectoryFiles;

    public static function makeDirectoryStructure(array $fileList, $isCheckBox = false, array $selectedFiles = []) {
        $tree = [];
        //echo "<pre>" . print_r($fileList, true) . "</pre> " . __LINE__;

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