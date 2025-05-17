<?php
namespace App\Core\Universal\Utils\Data\Response;
?>
<?php
enum ResponseType: string {
    case DATA    = "data";
    case JSON    = "json";

    public static function getByName(string $value): ?self {
        foreach (self::cases() as $case) {
            if ($case->name === $value) {
                return $case;
            }
        }
        return null;
    }

    public static function getByValue(string $value): ?self {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }
        return null;
    }
}
?>
