<?php
namespace RzSDK\Utils\String;
?>
<?php
?>
<?php
class StringHelper {
    public static function toRemoveWhitespace($string) {
        return preg_replace("/\s+/", "", trim($string));
    }

    public static function toSingleSpace($string) {
        return preg_replace("/\s+/", " ", trim($string));
    }

    public static function toUCFirst($string) {
        return ucfirst(strtolower($string));
    }

    public static function toUCWords($string) {
        return ucwords(strtolower($string));
    }

    // Convert ASCII to plaintext in PHP
    public static function toHex($string) {
        return strtoupper(bin2hex(iconv("UTF-8", "UCS-2", $string)));
    }

    // Convert ASCII to plaintext in PHP
    public static function toHexPlus($string) {
        return "U+" . strtoupper(bin2hex(iconv("UTF-8", "UCS-2", $string)));
    }

    // Convert ASCII to plaintext in PHP
    public static function toUHex($string) {
        $str = str_replace("\"", "", json_encode($string));
        if($str == $string) {
            return $str;
        }
        return strtoupper($str);
    }

    public static function toHtmlEntities($string) {
        //return htmlentities($string);
        return htmlentities($string, ENT_QUOTES, "UTF-8");
    }

    public static function toHtmlEntityDecode($string) {
        return html_entity_decode($string, ENT_QUOTES, "UTF-8");
    }

    public static function toAscii($char) {
        return ord($char);
    }

    public static function isUnicode($string) {
        return mb_strlen($string) != strlen($string);
    }

    public static function toSlugify($text, string $divider = "-") {
        $string = preg_replace("/[^A-Za-z0-9-]+/", " ", trim($text));
        $slug = preg_replace("/[-\s]+/", $divider, $string);
        $slug = strtolower($slug);
        return $slug;
    }

    private static function toSlugify01($text, string $divider = "-") {
        // replace non letter or digits by divider
        $text = preg_replace("~[^\pL\d]+~u", $divider, $text);

        // transliterate
        $text = iconv("utf-8", "us-ascii//TRANSLIT", $text);

        // remove unwanted characters
        $text = preg_replace("~[^-\w]+~", "~[^-\w]+~", $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace("~-+~", $divider, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return "n-a";
        }

        return $text;
    }
}
?>
<?php
/*foreach(mb_str_split($str) as $char) {
}
for($i = 0; $i < mb_strlen($str); $i++){
    $char = mb_substr($str, $i, 1, "UTF-8");
}*/
?>