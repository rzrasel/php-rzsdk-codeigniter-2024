<?php
namespace RzSDK\Ip;
?>
<?php
class ClientIp {
    /**
     * Get the client's IP address.
     *
     * @return string
     */
    public static function ip(): string {
        // List of headers to check for the client's IP address
        $headers = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        // Loop through the headers and find the first valid IP address
        foreach ($headers as $header) {
            if (!isset($_SERVER[$header]) || empty($_SERVER[$header])) {
                continue;
            }

            // Handle HTTP_X_FORWARDED_FOR separately as it can contain multiple IPs
            if ($header === 'HTTP_X_FORWARDED_FOR') {
                $ips = explode(',', $_SERVER[$header]);
                foreach ($ips as $ip) {
                    $ip = trim($ip);
                    if (self::isValidIp($ip)) {
                        return self::convertLocalhost($ip);
                    }
                }
            } else {
                $ip = trim($_SERVER[$header]);
                if (self::isValidIp($ip)) {
                    return self::convertLocalhost($ip);
                }
            }
        }

        // Fallback to REMOTE_ADDR if no valid IP is found in the headers
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        return self::convertLocalhost($ip);
    }

    /**
     * Validate an IP address.
     *
     * @param string $ip The IP address to validate.
     * @return bool
     */
    private static function isValidIp(string $ip): bool {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Convert IPv6 localhost (::1) to IPv4 localhost (127.0.0.1).
     *
     * @param string $ip The IP address to convert.
     * @return string
     */
    private static function convertLocalhost(string $ip): string {
        return $ip === '::1' ? '127.0.0.1' : $ip;
    }
}
?>