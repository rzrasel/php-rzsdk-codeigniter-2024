<?php
namespace RzSDK\Log;
?>
<?php
class DebugLog {
    public static function log($message) {
        echo "<br />";
        echo "<pre style=\"overflow-x: auto; white-space: pre-wrap; word-wrap: break-word; font-size: 12px;\">";
        //echo "<div style=\"margin: auto; width: 50%; border: 1px solid green; padding: 10px; border-radius: 10px;\">";
        echo "<div style=\"line-height: 16px; margin: auto; background: #4eaf51; color: #fdfdf9; border: 1px solid #3a833d; padding: 10px; border-radius: 10px;\">";
        //echo "<div style=\"margin: auto; background: #ef1e62; color: #fffbff; border: 1px solid #b2164b; padding: 10px; border-radius: 10px;\">";
        //echo "<div style=\"overflow-wrap: break-word; word-wrap: break-word; -ms-word-break: break-all; word-break: break-all; word-break: break-word; -ms-hyphens: auto; -moz-hyphens: auto; -webkit-hyphens: auto; hyphens: auto;\">";
        //is_instance($message)
        if(is_array($message) || is_object($message)) {
            //echo "<pre>";
            print_r($message);
            //echo "</pre>";
        } else {
            //echo $message;
            print_r($message);
        }
        echo "<br />";
        echo "<br />";

        $debugBacktrace = debug_backtrace();
        $printData = "File " . $debugBacktrace[0]["file"];
        if(count($debugBacktrace) > 1) {
            $class = $debugBacktrace[1]["class"];
            $method = $debugBacktrace[1]["function"];
            $printData .= " class " . $class . " method " . $method;
        }
        $printData .= " on line " . $debugBacktrace[0]["line"];
        echo $printData;
        echo "</div>";
        //echo "</div>";
        echo "</pre>";
        echo "<br />";
    }
}
?>