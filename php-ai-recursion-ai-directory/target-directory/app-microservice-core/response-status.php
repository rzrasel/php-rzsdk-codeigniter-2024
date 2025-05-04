<?php
namespace App\Microservice\Core\Utils\Type\Response;
?>
<?php
enum ResponseStatus: string {
    case SUCCESS    = "success";
    case ERROR      = "error";

    public static function getByName(string $value): ?self {
        foreach (self::cases() as $case) {
            if ($case->name === $value) {
                return $case;
            }
        }
        return null;
    }
}
?>