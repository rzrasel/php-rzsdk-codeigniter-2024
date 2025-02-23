<?php
namespace RzSDK\Encryption;
?>
<?php
//defined("RZ_SDK_BASEPATH") OR exit("No direct script access allowed");
//defined("RZ_SDK_WRAPPER") OR exit("No direct script access allowed");
?>
<?php
class OpensslEncryption {
    public static function encryptData($data, $key, $iv, $cipherMethod = "AES-256-CBC") {
        //global $cipherMethod, $key, $iv;
        return base64_encode(openssl_encrypt($data, $cipherMethod, $key, OPENSSL_RAW_DATA, $iv));
    }

    public static function decryptData($data, $key, $iv, $cipherMethod = "AES-256-CBC") {
        //global $cipherMethod, $key, $iv;
        return openssl_decrypt(base64_decode($data), $cipherMethod, $key, OPENSSL_RAW_DATA, $iv);
    }
}
?>