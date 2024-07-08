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
}
?>