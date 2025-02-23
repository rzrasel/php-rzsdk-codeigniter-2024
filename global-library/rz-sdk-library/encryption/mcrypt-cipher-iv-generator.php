<?php
namespace RzSDK\Encryption;
?>
<?php
//defined("RZ_SDK_BASEPATH") OR exit("No direct script access allowed");
//defined("RZ_SDK_WRAPPER") OR exit("No direct script access allowed");
?>
<?php
class McryptCipherIvGenerator {
    public static function opensslRandomIv($cipherMethod = "AES-256-CBC") {
        $ivLen = openssl_cipher_iv_length($cipherMethod);
        $isCryptoStrong = false;
        $iv = openssl_random_pseudo_bytes($ivLen, $isCryptoStrong);
        return $iv;
    }

    public static function opensslDigestIv($secretKey, $isBinary = true) {
        return openssl_digest($secretKey, "SHA256", $isBinary);
    }

    public static function hashIv($secretKey, $length = -1) {
        //$rand = rand(0, 9);
        if($length <= 0) {
            return hash("SHA256", $secretKey);
        }
        return substr(hash("SHA256", $secretKey), 0, $length);
    }
}
?>