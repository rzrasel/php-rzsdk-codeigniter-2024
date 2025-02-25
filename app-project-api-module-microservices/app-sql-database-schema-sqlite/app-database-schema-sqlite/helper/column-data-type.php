<?php
namespace App\DatabaseSchema\Helper\Data\Type;
?>
<?php
enum ColumnDataType: string {
    case BIGINT     = "bigint";
    case BOOLEAN    = "boolean";
    case DATE       = "date";
    case DATETIME   = "datetime";
    case INT        = "int";
    case INTEGER    = "integer";
    case TEXT       = "text";
    case TIMESTAMP  = "timestamp";
    case VARCHAR    = "varchar";
    case YEAR       = "year";

    public static function getByName($value): ?self {
        foreach(self::cases() as $case) {
             if ($case->name === $value) {
                return $case;
            }
        }
        return null;
    }

    public static function getByValue($value): ?self {
        foreach(self::cases() as $props) {
            /* if ($case->name === $enumName) {
                return $case;
            } */
            if($props->value === $value) {
                return $props;
            }
        }
        return null;
    }

    public static function onRecursionTraverse(callable $callback): string {
        $html = "";
        foreach (self::cases() as $case) {
            $html .= $callback($case);
        }
        return $html;
    }
}

/*// Usage with a callback function
$htmlOutput = "<ul>";
$htmlOutput .= RelationalKeyType::onRecursionTraverse(function (RelationalKeyType $key) {
    return "<li>{$key->name}</li>";
});
$htmlOutput .= "</ul>";

// Generate HTML list using callback
$htmlOutput = "<ul>";
$htmlOutput .= RelationalKeyType::onRecursionTraverse(fn(RelationalKeyType $key) => "<li>{$key->name}</li>");
$htmlOutput .= "</ul>";

// Output the final HTML
echo $htmlOutput;*/
?>
