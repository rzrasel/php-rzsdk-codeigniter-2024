<?php
namespace App\DatabaseSchema\Helper\Utils;
?>
<?php
enum DeleteActionType: string {
    case DATABASE_SCHEMA_DATA   = "delete_database_schema_data";
    case TABLE_DATA             = "delete_table_data";
    case COLUMN_DATA            = "delete_column_data";
    case COLUMN_KEY             = "delete_column_key";
    case COMPOSITE_KEY          = "delete_composite_key";
    case NONE                   = "";

    public static function getByValue($value): self {
        foreach(self::cases() as $props) {
            /* if ($case->name === $enumName) {
                return $case;
            } */
            if($props->value === $value) {
                return $props;
            }
        }
        return self::NONE;
    }
}
?>
