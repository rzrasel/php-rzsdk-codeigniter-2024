<?php
namespace RzSDK\String\Utils;
?>
<?php
use RzSDK\String\Utils\TextCase;
?>
<?php
class StringUtils {

    public static function mb_trim($string) {
        return mb_ereg_replace("^\s*([\s\S]*?)\s*$", "\1", $string);
    }

    /**
     * Make a string's first character uppercase multi-byte safely.
     */
    public static function mb_ucfirst_case(string $string, ?string $encoding = null): string {
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $firstChar = mb_convert_case($firstChar, MB_CASE_TITLE, $encoding);

        return $firstChar . mb_substr($string, 1, null, $encoding);
    }

    public static function mb_ucfirst($string, $encoding = null) {
        if (!$encoding) {
            $encoding = mb_internal_encoding();
        }

        return mb_strtoupper(mb_substr($string, 0, 1, $encoding), $encoding)
            . mb_substr($string, 1, mb_strlen($string, $encoding) - 1, $encoding);
    }

    /**
     * Make a string's first character lowercase multi-byte safely.
     */
    public static function mb_lcfirst(string $string, ?string $encoding = null): string {
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $firstChar = mb_convert_case($firstChar, MB_CASE_LOWER, $encoding);

        return $firstChar . mb_substr($string, 1, null, $encoding);
    }

    public static function mb_ucwords($string, $encoding = null) {
        if (!$encoding) {
            $encoding = mb_internal_encoding();
        }

        mb_regex_encoding($encoding);
        mb_ereg_search_init($string, "(\S)(\S*\s*)|(\s+)");
        $output = '';
        while ($match = mb_ereg_search_regs()) {
            $output .= $match[3] ? $match[3] : mb_strtoupper($match[1], $encoding) . $match[2];
        }

        return $output;
    }

    public static function removeWhitespace($string) {
        $string = trim($string);
        return preg_replace("/\s+/", " ", $string);
    }

    public static function toCaseConversion($string, TextCase $case = TextCase::UPPER) {
        if($case == TextCase::ALIKE) {
            $string = $string;
        } else if($case == TextCase::LOWER) {
            $string = mb_strtolower($string);
        } else if($case == TextCase::UCFIRST) {
            $string = mb_strtolower($string);
            $string = self::mb_ucfirst($string);
        } else if($case == TextCase::UCWORDS) {
            $string = mb_strtolower($string);
            $string = self::mb_ucwords($string);
        } else if($case == TextCase::UPPER) {
            $string = mb_strtoupper($string);
        }
        return $string;
    }
}
?>