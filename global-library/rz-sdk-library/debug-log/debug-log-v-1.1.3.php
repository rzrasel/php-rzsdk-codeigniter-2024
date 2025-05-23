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
<?php
class DebugLog {
    // Default styles for HTML output
    private static $htmlStyles = [
        'container' => 'line-height: 16px; margin: auto; background: #4eaf51; color: #fdfdf9; border: 1px solid #3a833d; padding: 10px; border-radius: 10px;',
        'pre' => 'overflow-x: auto; white-space: pre-wrap; word-wrap: break-word; font-size: 12px;',
    ];

    /**
     * Log a message, array, or object.
     *
     * @param mixed $message The message to log.
     * @param bool $htmlOutput Whether to format output for HTML (default: true).
     */
    public static function log($message, LogType $logType = LogType::INFORMATION, bool $htmlOutput = true): void {
        // Format the message
        $debugData = is_array($message) || is_object($message) ? print_r($message, true) : $message;

        // Get the debug backtrace
        $debugBacktrace = debug_backtrace();
        $file = $debugBacktrace[0]['file'] ?? 'Unknown file';
        $fileOnly = basename($file);
        $line = $debugBacktrace[0]['line'] ?? 'Unknown line';
        $class = $debugBacktrace[1]['class'] ?? 'Undefined';
        $classOnly = self::getClassNameWithoutNamespace($class);
        $method = $debugBacktrace[1]['function'] ?? 'Undefined';

        // Prepare the debug information
        $debugInfo = "<span style=\"background-color:#dddddd; text-transform: uppercase; font-weight: bold; color: #7c7e82; border-radius: 10px; padding: 1px 6px;\">Debug Information:</span> <span style=\"background-color:#dddddd; font-weight: bold; color: #7c7e82; border-radius: 10px; padding: 1px 6px;\">→ File With Path:</span> $file <span style=\"background-color:#dddddd; font-weight: bold; color: #7c7e82; border-radius: 10px; padding: 1px 6px;\">→ Class With Namespace:</span> $class <span style=\"background-color:#dddddd; font-weight: bold; color: #7c7e82; border-radius: 10px; padding: 1px 6px;\">→ File:</span> $fileOnly <span style=\"background-color:#dddddd; font-weight: bold; color: #7c7e82; border-radius: 10px; padding: 1px 6px;\">→ Class:</span> $classOnly <span style=\"background-color:#dddddd; font-weight: bold; color: #7c7e82; border-radius: 10px; padding: 1px 6px;\">→ Method:</span> $method <span style=\"background-color:#dddddd; font-weight: bold; color: #7c7e82; border-radius: 10px; padding: 1px 6px;\">→ Line:</span> $line";

        // Output in HTML or plain text
        if ($htmlOutput) {
            echo "<br />";
            echo "<pre style=\"" . self::$htmlStyles['pre'] . "\">";
            echo "<div style=\"" . self::$htmlStyles['container'] . "\">";
            echo htmlspecialchars($debugData); // Output the message
            echo "<br /><br />";
            echo nl2br($debugInfo); // Output debug info with line breaks
            echo "</div>";
            echo "</pre>";
            echo "<br />";
        } else {
            echo $debugData . "\n"; // Output the message
            echo $debugInfo . "\n"; // Output debug info
        }
    }

    /**
     * Extract the class name without the namespace.
     *
     * @param string $class The fully qualified class name.
     * @return string The class name without the namespace.
     */
    private static function getClassNameWithoutNamespace(string $class): string {
        $parts = explode('\\', $class);
        return end($parts);
    }

    /**
     * Set custom styles for HTML output.
     *
     * @param array $styles An associative array of styles (e.g., ['container' => '...', 'pre' => '...']).
     */
    public static function setHtmlStyles(array $styles): void {
        self::$htmlStyles = array_merge(self::$htmlStyles, $styles);
    }
}
?>
<?php
/*Example Usage
Logging in HTML Mode (Default)

use RzSDK\Log\DebugLog;

DebugLog::log("This is a debug message.");
DebugLog::log(["key" => "value"]);
DebugLog::log(new stdClass());*/

/*Logging in Plain Text Mode (for CLI)
use RzSDK\Log\DebugLog;

DebugLog::log("This is a debug message.", false);
DebugLog::log(["key" => "value"], false);
DebugLog::log(new stdClass(), false);*/

/*Customizing HTML Styles
use RzSDK\Log\DebugLog;

DebugLog::setHtmlStyles([
    'container' => 'background: #ef1e62; color: #fffbff; border: 1px solid #b2164b; padding: 10px; border-radius: 10px;',
    'pre' => 'font-size: 14px;',
]);

DebugLog::log("Custom styled debug message.");*/
?>
