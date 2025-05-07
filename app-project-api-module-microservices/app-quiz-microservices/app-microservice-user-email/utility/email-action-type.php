<?php
namespace App\Microservice\Type\Action\Email;
?>
<?php
enum EmailActionType: string {
    case GET = "get";
    case FIND = "find";
    case SELECT = "select";
    case INSERT = "insert";
    case UPDATE = "update";

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