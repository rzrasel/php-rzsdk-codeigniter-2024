<?php
namespace App\Microservice\Type\Status\Password;
?>
<?php
enum PasswordStatus: string {
    // active is default status
    case ACTIVE = "active";
    case INACTIVE = "inactive";
    case EXPIRED = "expired";

    public static function getByName(string $value): ?self {
        foreach (self::cases() as $case) {
            if ($case->name === $value) {
                return $case;
            }
        }
        return self::ACTIVE;
    }

    public static function getByValue(string $value): ?self {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }
        return self::ACTIVE;
    }
}
?>