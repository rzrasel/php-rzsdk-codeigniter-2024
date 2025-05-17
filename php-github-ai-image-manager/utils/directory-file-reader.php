<?php
namespace App\Microservice\Utils\Directory\File\Reader;
?>
<?php
class DirectoryFileReader {
    public static function traverseDirectoryRead($basePath, $files, $extensions = [], $readExtensions = []) {
        // Normalize extensions
        $extensions = self::normalizeExtensions($extensions);
        $readExtensions = self::normalizeExtensions($readExtensions);

        $html = "<div class=\"image-album\">\n";

        foreach ($files as $fileItem) {
            if (empty($fileItem['files'])) continue;

            $label = self::formatLabel($fileItem['label']);
            $html .= "  <div class=\"image-album-section\">\n";
            $html .= "    <div class=\"image-album-label\">\n";
            $html .= "      <h3><label>{$label}</label></h3>\n";
            $html .= "    </div>\n";
            $html .= "    <div class=\"image-gallery-grid\">\n";

            foreach ($fileItem['files'] as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                if (!empty($extensions) && !in_array($ext, $extensions)) continue;

                $fullPath = self::buildFullPath($basePath, $file);
                $filePath = htmlspecialchars($fullPath);
                $filename = htmlspecialchars(pathinfo($file, PATHINFO_FILENAME));

                if (in_array($ext, $readExtensions)) {
                    $html .= self::renderReadableFile($fullPath, $filePath, $filename);
                } else {
                    $html .= self::renderImageFile($fullPath, $filePath, $filename);
                }
            }

            $html .= "    </div>\n"; // Close image-gallery-grid
            $html .= "  </div>\n"; // Close image-album-section
        }

        $html .= "</div>\n"; // Close image-album

        return $html;
    }

    private static function normalizeExtensions($extensions) {
        if (!is_array($extensions)) {
            return !empty($extensions) ? [strtolower($extensions)] : [];
        }
        return array_map('strtolower', $extensions);
    }

    private static function formatLabel($label) {
        $label = htmlspecialchars($label);
        $label = str_replace(['-', '_'], ' ', $label);
        return ucwords(strtolower(trim($label)));
    }

    private static function buildFullPath($basePath, $file) {
        return !empty($basePath) ? $basePath . '/' . ltrim($file, '/') : $file;
    }

    private static function renderReadableFile($fullPath, $filePath, $filename) {
        $html = "      <div class=\"readable-file\">\n";
        if (file_exists($fullPath)) {
            $content = htmlspecialchars(file_get_contents($fullPath));
            $html .= "        <div class=\"file-content\">{$content}</div>\n";
        } else {
            $html .= "        <div class=\"file-not-found\">\n";
            $html .= "          <a href=\"{$filePath}\">{$filename}</a>\n";
            $html .= "          <p><em>File not found to read content.</em></p>\n";
            $html .= "        </div>\n";
        }
        $html .= "      </div>\n";
        return $html;
    }

    private static function renderImageFile($fullPath, $filePath, $filename) {
        $imageWidth = "200px";
        return "      <div class=\"image-gallery-grid-item\">\n" .
            "        <div class=\"image-gallery-item\">\n" .
            "          <a href=\"{$filePath}\" target=\"_blank\" title=\"{$filename}\">\n" .
            "            <img src=\"{$filePath}\" alt=\"{$filename}\" style=\"width: {$imageWidth}; border: 0;\"/>\n" .
            "          </a>\n" .
            "        </div>\n" .
            "      </div>\n";
    }
}
?>
<?php
/*class DirectoryFileReader {
    public static function traverseDirectoryRead($basePath, $files, $extensions = [], $readExtensions = []) {
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

        $html = "<div class=\"image-album\">\n";

        foreach ($files as $fileItem) {
            $label = htmlspecialchars($fileItem['label']);
            $label = str_replace(['-', '_'], ' ', $label);
            $label = ucwords(strtolower(trim($label)));
            $html .= "  <div class=\"image-album-label\">\n";
            $html .= "    <h3><label>{$label}</label></h3>\n";
            $html .= "  </div>\n";
            $html .= "  <div class=\"image-gallery-image-list\">\n";

            $isFirstItem = true;

            foreach ($fileItem['files'] as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                if (empty($extensions) || in_array($ext, $extensions)) {
                    $filename = htmlspecialchars(pathinfo($file, PATHINFO_FILENAME));
                    $fullPath = $file;
                    if(!empty($basePath)) {
                        $fullPath = $basePath . "/" . $file;
                    }
                    $filePath = htmlspecialchars($fullPath);

                    if (in_array($ext, $readExtensions)) {
                        if (file_exists($fullPath)) {
                            $content = htmlspecialchars(file_get_contents($fullPath));
                            $html .= "    <div class=\"image-album-description\">{$content}</div>\n";
                        } else {
                            $html .= "    <div class=\"image-album-file-not-found\"><a href=\"{$filePath}\">{$filename}</a>\n";
                            $html .= "    <p><em>File not found to read content.</em></p></div>\n";
                        }
                    } else {
                        if($isFirstItem) {
                            $html .= "    <div class=\"image-gallery-image-grid\">\n";
                        }
                        $imageWidth = "200px";
                        //$html .= "    <a href=\"{$filePath}\">{$filename}</a>\n";
                        $html .= "    <div class=\"image-gallery-image-item\"><a href=\"{$filePath}\" target=\"_blank\">\n        <img width=\"{$imageWidth}\" src=\"{$filePath}\" style=\"width: {$imageWidth}; border: 0;\"/>\n    </a><div>\n";
                    }
                }
            }
            $html .= "    </div>\n";

            $html .= "  </div>\n";
        }

        $html .= "</div>\n";

        return $html;
    }
}*/
?>