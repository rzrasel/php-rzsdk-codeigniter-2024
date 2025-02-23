<?php
namespace RzSDK\URL;
?>
<?php
class SiteUrl {
    /**
     * Get the full URL of the current request, including query parameters.
     *
     * @return string
     */
    public static function getFullUrl(): string {
        $protocol = self::getProtocol();
        $host = self::getHost();
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        return rtrim($protocol . $host . $uri, "/");
    }

    /**
     * Get the URL of the current request without query parameters.
     *
     * @return string
     */
    public static function getUrlOnly(): string {
        $protocol = self::getProtocol();
        $host = self::getHost();
        $uri = explode("?", $_SERVER['REQUEST_URI'] ?? '', 2)[0];
        return rtrim($protocol . $host . $uri, "/");
    }

    /**
     * Get the base URL of the current request (up to the script's directory).
     *
     * @return string
     */
    public static function getBaseUrl(): string {
        $protocol = self::getProtocol();
        $host = self::getHost();
        $scriptPath = dirname($_SERVER['SCRIPT_NAME'] ?? '');
        return rtrim($protocol . $host . $scriptPath, "/");
    }

    /**
     * Detects the protocol (HTTP or HTTPS).
     *
     * @return string
     */
    private static function getProtocol(): string {
        if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) {
            return "https://";
        }
        return "http://";
    }

    /**
     * Gets and sanitizes the HTTP host.
     *
     * @return string
     */
    private static function getHost(): string {
        return filter_var($_SERVER['HTTP_HOST'] ?? 'localhost', FILTER_SANITIZE_URL);
    }
}
?>
<?php
/*echo \RzSDK\URL\SiteUrl::getFullUrl();
// Example output: https://example.com/path/to/page?query=param*/

/*echo \RzSDK\URL\SiteUrl::getUrlOnly();
// Example output: https://example.com/path/to/page*/

/*echo \RzSDK\URL\SiteUrl::getBaseUrl();
// Example output: https://example.com/path/to*/
?>
