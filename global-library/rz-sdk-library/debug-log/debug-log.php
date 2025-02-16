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
        'container' => 'line-height: 18px; margin: auto; padding: 10px; border-radius: 10px;',
        'pre' => 'line-height: 18px; overflow-x: auto; white-space: pre-wrap; word-wrap: break-word; font-size: 12px;',
    ];

    // Styles for each log type
    private static $logTypeStyles = [
        LogType::INFORMATION->value => [
            'background' => '#4eaf51', // Green
            'color' => '#fdfdf9', // Light text
            'border' => '#3a833d', // Darker green
        ],
        LogType::ERROR->value => [
            'background' => '#ef1e62', // Red
            'color' => '#fffbff', // Light text
            'border' => '#b2164b', // Darker red
        ],
        LogType::MESSAGE->value => [
            'background' => '#2196F3', // Blue
            'color' => '#ffffff', // White text
            'border' => '#1a73e8', // Darker blue
        ],
        LogType::WARNING->value => [
            'background' => '#FFC107', // Yellow
            'color' => '#000000', // Black text
            'border' => '#FFA000', // Darker yellow
        ],
    ];

    /**
     * Log a message, array, or object.
     *
     * @param mixed $message The message to log.
     * @param LogType $logType The type of log (default: LogType::INFORMATION).
     * @param bool $htmlOutput Whether to format output for HTML (default: true).
     */
    public static function log($message, LogType $logType = LogType::INFORMATION, bool $htmlOutput = true, $traceIndex = 0): void {
        // Format the message
        $debugData = is_array($message) || is_object($message) ? print_r($message, true) : $message;
        //$traceIndex = 0;

        // Get the debug backtrace
        $debugBacktrace = debug_backtrace();
        //echo "Count: " . count($debugBacktrace);
        if($traceIndex >= count($debugBacktrace)) {
            $traceIndex = count($debugBacktrace) - 1;
        }
        //echo "<pre>" . print_r($debugBacktrace, true) . "</pre>";
        $file = $debugBacktrace[$traceIndex]['file'] ?? 'Unknown file';
        $fileOnly = basename($file);
        $line = $debugBacktrace[$traceIndex]['line'] ?? 'Unknown line';
        $class = $debugBacktrace[($traceIndex + 1)]['class'] ?? 'Undefined';
        $classOnly = self::getClassNameWithoutNamespace($class);
        $method = $debugBacktrace[($traceIndex + 1)]['function'] ?? 'Undefined';

        // Prepare the debug information
        $debugInfo = "<span style=\"background-color:#dddddd; text-transform: uppercase; font-weight: bold; color: #7c7e82; border-radius: 10px; padding: 1px 6px;\">Debug Information:</span> <span style=\"background-color:#dddddd; font-weight: bold; color: #7c7e82; border-radius: 10px; padding: 1px 6px;\">→ File With Path:</span> $file <span style=\"background-color:#dddddd; font-weight: bold; color: #7c7e82; border-radius: 10px; padding: 1px 6px;\">→ Class With Namespace:</span> $class <span style=\"background-color:#dddddd; font-weight: bold; color: #7c7e82; border-radius: 10px; padding: 1px 6px;\">→ File:</span> $fileOnly <span style=\"background-color:#dddddd; font-weight: bold; color: #7c7e82; border-radius: 10px; padding: 1px 6px;\">→ Class:</span> $classOnly <span style=\"background-color:#dddddd; font-weight: bold; color: #7c7e82; border-radius: 10px; padding: 1px 6px;\">→ Method:</span> $method <span style=\"background-color:#dddddd; font-weight: bold; color: #7c7e82; border-radius: 10px; padding: 1px 6px;\">→ Line:</span> $line";

        // Get styles for the current log type
        $logTypeStyle = self::$logTypeStyles[$logType->value] ?? self::$logTypeStyles[LogType::INFORMATION->value];
        $containerStyle = self::$htmlStyles['container'] . sprintf(
                'background: %s; color: %s; border: 1px solid %s;',
                $logTypeStyle['background'],
                $logTypeStyle['color'],
                $logTypeStyle['border']
            );

        // Output in HTML or plain text
        if ($htmlOutput) {
            echo "<br />";
            echo "<pre style=\"" . self::$htmlStyles['pre'] . "\">";
            echo "<div style=\"" . $containerStyle . "\">";
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
