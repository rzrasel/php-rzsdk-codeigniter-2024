<?php
namespace App\Microservice\Type\Verification\Status\Email;
?>
<?php
enum EmailVerificationStatus: string {
    // pending is default status
    case PENDING = "pending";
    case VERIFIED = "verified";
    case EXPIRED = "expired";
    case BLOCKED = "blocked";

    public static function getByName(string $value): ?self {
        foreach (self::cases() as $case) {
            if ($case->name === $value) {
                return $case;
            }
        }
        return self::PENDING;
    }

    public static function getByValue(string $value): ?self {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }
        return self::PENDING;
    }
}
?>