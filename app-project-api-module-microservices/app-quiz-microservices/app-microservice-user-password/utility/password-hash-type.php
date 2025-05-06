<?php
namespace App\Microservice\Type\Hash\Password;
?>
<?php
enum PasswordHashType: string {
    // password_hash is default status
    case PASSWORD_HASH = "password_hash";
    case SHA256 = "SHA256";
    case BCRYPT = "bcrypt";
    case ARGON2 = "argon2";

    public static function getByName(string $value): ?self {
        foreach (self::cases() as $case) {
            if ($case->name === $value) {
                return $case;
            }
        }
        return self::PASSWORD_HASH;
    }

    public static function getByValue(string $value): ?self {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }
        return self::PASSWORD_HASH;
    }
}
?>