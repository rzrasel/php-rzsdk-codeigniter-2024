<?php
namespace RzSDK\Include\Import;
?>
<?php
enum PathType: string {
    case REAL_PATH = "realpath";
    case RELATIVE_PATH = "relativepath";
    public static function getByValue($value): self {
        foreach(self::cases() as $props) {
            /* if ($case->name === $enumName) {
                return $case;
            } */
            if($props->value === $value) {
                return $props;
            }
        }
        return self::REAL_PATH;
    }
}
//$pathTypeBeen = PathType::RELATIVE_PATH;
?>
