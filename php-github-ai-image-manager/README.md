<?php
$inputData = [
    'label' => 'user-image',
    'files' => [
        'user-image/Jtlps4MZ-2025-05-17-193830-2138.png',
        'user-image/Jtlps4MZ-2025-05-17-193830-2499.png',
        'user-image/Jtlps4MZ-2025-05-17-193830-8085.png',
        'user-image/memory/Jtlps4MZ-2025-05-17-193830-1758.png',
        'user-image/memory/Jtlps4MZ-2025-05-17-193830-4270.png',
        'user-image/memory/Jtlps4MZ-2025-05-17-193830-8688.png',
        'user-image/remember/Jtlps4MZ-2025-05-17-193830-1831.png',
        'user-image/remember/Jtlps4MZ-2025-05-17-193830-2003.png',
        'user-image/remember/Jtlps4MZ-2025-05-17-193830-4901.png',
    ],
];
?>
<?php
$multyDimentionData = [
    [
        'label' => 'root',
        'files' => [
            'Jtlps4MZ-2025-05-17-193830-3521.png',
            'Jtlps4MZ-2025-05-17-193830-4361.png',
            'Jtlps4MZ-2025-05-17-193830-7755.png',
        ],
    ],
    [
        'label' => 'profile-image',
        'files' => [
            'profile-image/496923263_122101484240867325_8319934615767361976_n.jpg',
            'profile-image/496927982_122101505162867325_3553919011357420308_n (1).png',
            'profile-image/496927982_122101505162867325_3553919011357420308_n.jpg',
            'profile-image/496927982_122101505162867325_3553919011357420308_n.png',
            'profile-image/Jtlps4MZ-2025-05-17-193830-2020.png',
            'profile-image/Jtlps4MZ-2025-05-17-193830-2553.png',
            'profile-image/Jtlps4MZ-2025-05-17-193830-3164.png',
            'profile-image/Prompt_a_romantic_dancing_ballroom_scene_hyperrealistic_Bangladeshi_dancing_couple_in_a_ballroom_Ban_3545860015.png',
            'profile-image/Prompt_a_romantic_dancing_ballroom_scene_hyperrealistic_Bangladeshi_dancing_couple_in_a_ballroom_Ban_935589398.png',
            'profile-image/Prompt_a_romantic_dancing_ballroom_scene_hyperrealistic_Bangladeshi_dancing_couple_in_a_ballroom_Ban_946174770.png',
            'profile-image/a_bangladeshi_woma_image_.png',
            'profile-image/a_romantic_dancing_image_.png',
            'profile-image/girl-sits-on-lap-man-260nw-1649746084.png',
            'profile-image/magicstudio-art (29).jpg',
            'profile-image/magicstudio-art (30).jpg',
            'profile-image/magicstudio-art (31).jpg',
            'profile-image/magicstudio-art (32).jpg',
            'profile-image/passion-girl-sits-on-lap-260nw-1605661207 (1).png',
        ],
    ],
    [
        'label' => 'user-image',
        'files' => [
            'user-image/Jtlps4MZ-2025-05-17-193830-2138.png',
            'user-image/Jtlps4MZ-2025-05-17-193830-2499.png',
            'user-image/Jtlps4MZ-2025-05-17-193830-8085.png',
            'user-image/memory/Jtlps4MZ-2025-05-17-193830-1758.png',
            'user-image/memory/Jtlps4MZ-2025-05-17-193830-4270.png',
            'user-image/memory/Jtlps4MZ-2025-05-17-193830-8688.png',
            'user-image/remember/Jtlps4MZ-2025-05-17-193830-1831.png',
            'user-image/remember/Jtlps4MZ-2025-05-17-193830-2003.png',
            'user-image/remember/Jtlps4MZ-2025-05-17-193830-4901.png',
        ],
    ],
];
?>
<?php
namespace App\Microservice\Utils\Directory\Image\Gallery;
?>
<?php
class DirectoryFullImageGallery {
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

- DirectoryFullImageGallery class nicely work for $multyDimentionData want to work for both $multyDimentionData and $inputData even dynamic dimention data