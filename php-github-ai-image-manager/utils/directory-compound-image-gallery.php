<?php
namespace App\Microservice\Utils\Directory\Image\Gallery;
?>
<?php
class DirectoryCompoundImageGallery {
    public static function traverseAndGenerateHtmlGallery($basePath, $files, $extensions = []) {
        if (!is_array($extensions)) {
            $extensions = !empty($extensions) ? [strtolower($extensions)] : [];
        } else {
            $extensions = array_map('strtolower', $extensions);
        }

        $html = '<div class="gallery-container" style="display:flex;flex-wrap:wrap;gap:20px;">';

        foreach ($files as $item) {
            $label = htmlspecialchars($item['label']);
            $count = intval($item['count']);
            $fileList = $item['files'];

            if (empty($fileList)) continue;

            $coverImage = $fileList[0];
            $ext = strtolower(pathinfo($coverImage, PATHINFO_EXTENSION));

            if (!empty($extensions) && !in_array($ext, $extensions)) {
                continue;
            }

            $imagePath = rtrim($basePath, '/') . '/' . $coverImage;
            $title = htmlspecialchars($label);
            $title = str_replace(['-', '_'], ' ', $title);
            $title = ucwords(strtolower(trim($title)));

            $html .= '
                <a href="item-gallery-' . $label . '.html" class="album" style="text-decoration:none;color:inherit;width:200px;">
                    <div style="border:1px solid #ccc;border-radius:8px;overflow:hidden;">
                        <img src="' . htmlspecialchars($imagePath) . '" alt="' . $label . '" style="width:100%;height:150px;object-fit:cover;">
                        <div style="padding:10px;text-align:center;">
                            <strong>' . $title . '</strong><br>
                            <span>' . $count . ' item' . ($count > 1 ? 's' : '') . '</span>
                        </div>
                    </div>
                </a>
            ';
        }

        $html .= '</div>';
        return $html;
    }
}
?>