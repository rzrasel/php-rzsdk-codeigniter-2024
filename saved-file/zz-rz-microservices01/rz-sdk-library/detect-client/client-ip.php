<?php
namespace RzSDK\Device;
?>
<?php
class ClientIp {
    public static function ip() {
        $ip = "";

        if (isset($_SERVER["HTTP_CLIENT_IP"]) && !empty($_SERVER["HTTP_CLIENT_IP"])) {
            // Check if the IP is from a shared internet
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && !empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            // Check if the IP is passed from a proxy
            $ips = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"]);
            foreach ($ips as $ip_address) {
                // Trim whitespace and check if the IP address is valid
                $ip_address = trim($ip_address);
                if (filter_var($ip_address, FILTER_VALIDATE_IP)) {
                    $ip = $ip_address;
                    break;
                }
            }
        } elseif (isset($_SERVER["HTTP_X_FORWARDED"]) && !empty($_SERVER["HTTP_X_FORWARDED"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED"];
        } elseif (isset($_SERVER["HTTP_X_CLUSTER_CLIENT_IP"]) && !empty($_SERVER["HTTP_X_CLUSTER_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_X_CLUSTER_CLIENT_IP"];
        } elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]) && !empty($_SERVER["HTTP_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_FORWARDED"]) && !empty($_SERVER["HTTP_FORWARDED"])) {
            $ip = $_SERVER["HTTP_FORWARDED"];
        } else {
            // Fallback to REMOTE_ADDR if no other header contains the IP address
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        // Validate the found IP address
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            $ip = "UNKNOWN";
        }

        if($ip == "::1") {
            $ip = "127.0.0.1";
        }

        return $ip;
    }
}
?>