<?php
namespace App\Microservice\Core\Utils\Type\Database;
?>
<?php
enum DatabaseType: string {
    case MYSQL = "mysql";
    case SQLITE = "sqlite";

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