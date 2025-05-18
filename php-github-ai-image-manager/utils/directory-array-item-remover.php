<?php
namespace App\Microservice\Utils\Directory\Array\Item\Remover;
?>
<?php
class DirectoryArrayItemRemover {
    /**
     * Remove files with given extensions from the file list.
     *
     * @param array $files The directory array (like your $array).
     * @param array|string $extensions Extensions to remove (case-insensitive).
     * @return array Filtered array with files removed by extension.
     */
    public static function traverseDirectoryRemove($files, $extensions = []) {
        // Normalize $extensions to lowercase array
        if (!is_array($extensions)) {
            $extensions = !empty($extensions) ? [strtolower($extensions)] : [];
        } else {
            $extensions = array_map('strtolower', $extensions);
        }

        // Result array
        $filtered = [];

        // Traverse each directory label and file list
        foreach ($files as $item) {
            $label = $item['label'];
            $fileList = $item['files'];

            // Filter files that do NOT have the extensions to remove
            $filteredFiles = array_filter($fileList, function($file) use ($extensions) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                // Keep file if its extension is NOT in $extensions
                return !in_array($ext, $extensions);
            });

            // Add to result if files remain
            $filtered[] = [
                'label' => $label,
                'files' => array_values($filteredFiles), // reindex array
            ];
        }

        return $filtered;
    }
}
?>