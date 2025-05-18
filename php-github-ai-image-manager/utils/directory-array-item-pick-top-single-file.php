<?php
namespace App\Microservice\Utils\Directory\Array\Item\Pick\Top;
?>
<?php
class DirectoryArrayItemPickTopSingleFile {
    public static function traverseDirectorySingleFile($files, $extensions = []) {
        // Normalize extensions to lowercase array
        if (!is_array($extensions)) {
            $extensions = !empty($extensions) ? [strtolower($extensions)] : [];
        } else {
            $extensions = array_map('strtolower', $extensions);
        }

        $result = [];

        foreach ($files as $item) {
            $label = $item['label'];
            $fileList = $item['files'];
            $matchedFile = null;

            foreach ($fileList as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (empty($extensions) || in_array($ext, $extensions)) {
                    $matchedFile = $file;
                    break; // Stop after first match
                }
            }

            if ($matchedFile !== null) {
                $result[] = [
                    'label' => $label,
                    'count' => count($fileList),
                    'files' => [$matchedFile],
                ];
            }
        }

        return $result;
    }
}
?>