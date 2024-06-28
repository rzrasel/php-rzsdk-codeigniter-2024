<?php
namespace RzSDK\URL;
?>
<?php
class SiteUrl {
    public static function getFullUrl() {
        return rtrim((empty($_SERVER["HTTPS"]) ? "http" : "https") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], "/");
    }

    public static function getUrlOnly() {
        $uriParts = explode("?", $_SERVER["REQUEST_URI"], 2);
        return rtrim((empty($_SERVER["HTTPS"]) ? "http" : "https") . "://" . $_SERVER["HTTP_HOST"] . $uriParts[0], "/");
    }

    public static function getBaseUrl() {
        $phpSelf = $_SERVER["PHP_SELF"];
        $explode = explode("/", $phpSelf);
        $phpSelf = end($explode);
        //
        $uriParts = explode("?", $_SERVER["REQUEST_URI"], 2);
        $url = rtrim((empty($_SERVER["HTTPS"]) ? "http" : "https") . "://" . $_SERVER["HTTP_HOST"] . $uriParts[0], "/");
        //
        $explode = explode("/", $url);
        $baseUrl = end($explode);
        //
        if($phpSelf == $baseUrl) {
            //$baseUrl = rtrim(basename($url), "/");
            $baseUrl = rtrim(dirname($url), "/");
        } else {
            $baseUrl = $url;
        }
        return $baseUrl;
    }
}
?>