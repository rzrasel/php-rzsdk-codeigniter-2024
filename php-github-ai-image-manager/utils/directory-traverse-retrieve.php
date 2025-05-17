<?php
namespace App\Microservice\Utils\Directory\Traverse;
?>
<?php

class DirectoryTraverseRetrieve {
    public static function traverseDirectoryRetrieve($dir, $label = "root", $path = "", $level = 0, $maxLevel = 0, $extensions = [], &$result = []) {
        // Normalize extensions
        if (!is_array($extensions)) {
            $extensions = !empty($extensions) ? [strtolower($extensions)] : [];
        } else {
            $extensions = array_map('strtolower', $extensions);
        }

        // Initialize the current label's entry if it doesn't exist
        if (!isset($result[$label])) {
            $result[$label] = [
                "label" => $label,
                "files" => []
            ];
        }

        foreach ($dir as $key => $value) {
            if ($key === 'level') continue;

            if ($key === 'files' && is_array($value)) {
                foreach ($value as $file) {
                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    if (empty($extensions) || in_array($ext, $extensions)) {
                        $filePath = ltrim(($path ? $path . '/' : '') . $file, '/');
                        $result[$label]["files"][] = $filePath;
                    }
                }
            } elseif (is_array($value)) {
                $newLabel = ($level < $maxLevel) ? $key : $label;
                $newPath = $path === "" ? $key : $path . '/' . $key;
                self::traverseDirectoryRetrieve($value, $newLabel, $newPath, $level + 1, $maxLevel, $extensions, $result);
            }
        }

        // Return the formatted result when we're at the top level
        if ($level === 0) {
            return array_values($result);
        }

        // For recursive calls, return the modified result array by reference
        return null;
    }

    public static function traverseDirectoryRetrievePlain($dir, $path = "", $extensions = [], &$result = []) {
        // Normalize extensions to lowercase array
        if (!is_array($extensions)) {
            $extensions = !empty($extensions) ? [strtolower($extensions)] : [];
        } else {
            $extensions = array_map('strtolower', $extensions);
        }

        foreach ($dir as $key => $value) {
            if ($key === 'files' && is_array($value)) {
                foreach ($value as $file) {
                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    if (empty($extensions) || in_array($ext, $extensions)) {
                        $result[] = ltrim($path . '/' . $file, '/');
                    }
                }
            } elseif (is_array($value)) {
                $newPath = $path === "" ? $key : $path . '/' . $key;
                self::traverseDirectoryRetrievePlain($value, $newPath, $extensions, $result);
            }
        }

        return $result;
    }
}

?>