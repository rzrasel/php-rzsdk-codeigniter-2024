<?php
namespace App\Microservice\Utils\Directory\File\Rearrange;
?>
<?php
class DirectoryFileRearrange {
    public static function traverseDirectoryRearrange($files, $extensions = [], $readExtensions = []) {
        if (!is_array($extensions)) {
            $extensions = !empty($extensions) ? [strtolower($extensions)] : [];
        } else {
            $extensions = array_map('strtolower', $extensions);
        }

        if (!is_array($readExtensions)) {
            $readExtensions = !empty($readExtensions) ? [strtolower($readExtensions)] : [];
        } else {
            $readExtensions = array_map('strtolower', $readExtensions);
        }

        $result = [];

        foreach ($files as $item) {
            $label = $item['label'];
            $fileList = $item['files'];

            $readFiles = [];
            $otherFiles = [];

            foreach ($fileList as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                // Skip files not in allowed extensions
                if (!empty($extensions) && !in_array($ext, $extensions)) {
                    continue;
                }

                if (in_array($ext, $readExtensions)) {
                    $readFiles[] = $file;
                } else {
                    $otherFiles[] = $file;
                }
            }

            $result[] = [
                'label' => $label,
                'files' => array_merge($readFiles, $otherFiles),
            ];
        }

        return $result;
    }
}
?>