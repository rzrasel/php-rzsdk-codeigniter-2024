<?php
namespace RzSDK\Log;
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
     * @param int $traceIndex The index of the trace to use (default: 0).
     * @param string|null $upClass The class to look up in the trace (optional).
     */
    public static function log($message, LogType $logType = LogType::MESSAGE, bool $htmlOutput = true, int $traceIndex = 1, ?string $upClass = __CLASS__): void {
        // Format the message
        $debugData = self::formatMessage($message);
        if($traceIndex <= 0) {
            $traceIndex = 1;
        }
        if($traceIndex <= 1) {
            // Get the debug backtrace
            $traceIndex = self::calculateTraceIndex($traceIndex, $upClass);
        }
        $debugInfo = self::getDebugInfo($traceIndex, $logType);

        // Get styles for the current log type
        $containerStyle = self::getContainerStyle($logType);

        // Output in HTML or plain text
        if ($htmlOutput) {
            self::outputHtml($debugData, $debugInfo, $containerStyle);
        } else {
            self::outputPlainText($debugData, $debugInfo);
        }
    }

    /**
     * Format the message for logging.
     *
     * @param mixed $message The message to format.
     * @return string The formatted message.
     */
    private static function formatMessage($message): string {
        if(empty($message)) {
            if(is_null($message)) {
                $message = "null";
                return $message;
            }
            return is_array($message) || is_object($message) ? print_r($message, true) : $message;
        }
        return is_array($message) || is_object($message) ? print_r($message, true) : $message;
    }

    /**
     * Calculate the trace index based on the provided parameters.
     *
     * @param int $traceIndex The initial trace index.
     * @param string|null $upClass The class to look up in the trace.
     * @return int The calculated trace index.
     */
    private static function calculateTraceIndex(int $traceIndex, ?string $upClass): int {
        $debugBacktrace = debug_backtrace();

        if(!empty($upClass)) {
            $isExpectedClassFound = false;
            $isUpClassFound = false;
            $upClassIndex = 0;

            foreach($debugBacktrace as $trace) {
                if(!empty($trace['class'])) {
                    if ($upClass == $trace['class']) {
                        $isExpectedClassFound = true;
                    }
                    if ($isExpectedClassFound && $upClass != $trace['class']) {
                        $isUpClassFound = true;
                    }
                    if (!$isUpClassFound) {
                        $upClassIndex++;
                    }
                }
            }

            $traceIndex = $upClassIndex - 1;
        }

        // Ensure the trace index is within bounds
        return max(1, min($traceIndex, count($debugBacktrace) - 1));
    }

    private static function getDebugLabelArrowStyle() {
        return "content: ''; position: absolute; top: 50%; transform: translateY(-50%); right: -6px; width: 0; height: 0; border-left: 10px solid #dddddd; border-top: 7px solid transparent; border-bottom: 7px solid transparent;";
    }

    /**
     * Get debug information from the backtrace.
     *
     * @param int $traceIndex The index of the trace to use.
     * @param LogType $logType The type of log.
     * @return string The formatted debug information.
     */
    private static function getDebugInfo(int $traceIndex, LogType $logType): string {
        //echo $traceIndex;
        $debugBacktrace = debug_backtrace();
        //echo "<pre>" . print_r($debugBacktrace, true) . "</pre>";
        if($traceIndex >= count($debugBacktrace)) {
            $traceIndex = count($debugBacktrace) - 1;
        }
        //echo "traceIndex: $traceIndex";
        $file = $debugBacktrace[$traceIndex]['file'] ?? 'Unknown file';
        $fileOnly = basename($file);
        $line = $debugBacktrace[$traceIndex]['line'] ?? 'Unknown line';
        $class = $debugBacktrace[$traceIndex + 1]['class'] ?? 'Undefined';
        $classOnly = self::getClassNameWithoutNamespace($class);
        $method = $debugBacktrace[$traceIndex + 1]['function'] ?? 'Undefined';

        return sprintf(
            "<div style=\"line-height: 14px; cursor: default;\"><span style=\"%s\">Debug %s:</span> <span style=\"%s\">→ File With Path:<span style=\"%s\"></span></span>&nbsp;&nbsp;%s
<span style=\"%s\">→ File:<span style=\"%s\"></span></span>&nbsp;&nbsp;%s
<span style=\"%s\">→ Class With Namespace:<span style=\"%s\"></span></span>&nbsp;&nbsp;%s
<span style=\"%s\">→ Class:<span style=\"%s\"></span></span>&nbsp;&nbsp;%s&nbsp;<span style=\"%s\">→ Method:<span style=\"%s\"></span></span>&nbsp;&nbsp;%s&nbsp;<span style=\"%s\">→ Line:<span style=\"%s\"></span></span>&nbsp;&nbsp;%s</div>",
            self::getDebugLabelStyle(),
            $logType->value,
            self::getDebugArrowLabelStyle(),
            self::getDebugLabelArrowStyle(),
            htmlspecialchars($file),
            self::getDebugArrowLabelStyle(),
            self::getDebugLabelArrowStyle(),
            htmlspecialchars($fileOnly),
            self::getDebugArrowLabelStyle(),
            self::getDebugLabelArrowStyle(),
            htmlspecialchars($class),
            self::getDebugArrowLabelStyle(),
            self::getDebugLabelArrowStyle(),
            htmlspecialchars($classOnly),
            self::getDebugArrowLabelStyle(),
            self::getDebugLabelArrowStyle(),
            htmlspecialchars($method),
            self::getDebugArrowLabelStyle(),
            self::getDebugLabelArrowStyle(),
            htmlspecialchars($line)
        );
    }

    /**
     * Get the style for debug labels.
     *
     * @return string The CSS style for debug labels.
     */
    private static function getDebugLabelStyle(): string {
        return 'display: inline-block; background-color:#dddddd; text-transform: uppercase; font-weight: bold; color: #7c7e82; border-radius: 10px; padding: 1px 6px; margin: 2px 0px;';
    }

    private static function getDebugArrowLabelStyle(): string {
        return 'display: inline-block; position: relative; background-color:#dddddd; font-weight: bold; color: #7c7e82; border-radius: 10px; padding: 1px 6px; margin: 2px 0px;';
    }

    /**
     * Get the container style for the log type.
     *
     * @param LogType $logType The type of log.
     * @return string The CSS style for the container.
     */
    private static function getContainerStyle(LogType $logType): string {
        $logTypeStyle = self::$logTypeStyles[$logType->value] ?? self::$logTypeStyles[LogType::INFORMATION->value];
        return self::$htmlStyles['container'] . sprintf(
                'background: %s; color: %s; border: 1px solid %s;',
                $logTypeStyle['background'],
                $logTypeStyle['color'],
                $logTypeStyle['border']
            );
    }

    /**
     * Output the log in HTML format.
     *
     * @param string $debugData The formatted message.
     * @param string $debugInfo The debug information.
     * @param string $containerStyle The container style.
     */
    private static function outputHtml(string $debugData, string $debugInfo, string $containerStyle): void {
        echo "<br />";
        echo "<pre style=\"" . self::$htmlStyles['pre'] . "\">";
        echo "<div style=\"" . $containerStyle . "\">";
        echo htmlspecialchars($debugData); // Output the message
        echo "<br /><br />";
        //echo nl2br($debugInfo); // Output debug info with line breaks
        echo $debugInfo;
        echo "</div>";
        echo "</pre>";
        echo "<br />";
    }

    /**
     * Output the log in plain text format.
     *
     * @param string $debugData The formatted message.
     * @param string $debugInfo The debug information.
     */
    private static function outputPlainText(string $debugData, string $debugInfo): void {
        echo $debugData . "\n"; // Output the message
        echo $debugInfo . "\n"; // Output debug info
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
