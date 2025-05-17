<?php
namespace App\Microservice\AI\Directory\Scanner\Util;
?>
<?php
class ExtractPathUtil {
    public static function extractPaths($data, $key = "path") {
        $paths = [];

        foreach ($data as $item) {
            if (is_array($item)) {
                if (isset($item[$key])) {
                    $paths[] = $item[$key];
                } else {
                    // Nested array - recurse
                    $paths = array_merge($paths, self::extractPaths($item));
                }
            }
        }

        return $paths;
    }
}
?>