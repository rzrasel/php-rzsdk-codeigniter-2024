<?php
namespace RzSDK\Log;
?>
<?php
enum LogType: string {
    case INFORMATION = "information";
    case ERROR = "error";
    case MESSAGE = "message";
    case WARNING = "warning";
    public static function getByValue($value): self {
        foreach(self::cases() as $props) {
            /* if ($case->name === $enumName) {
                return $case;
            } */
            if($props->value === $value) {
                return $props;
            }
        }
        return self::INFORMATION;
    }
}
?>
