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
        $encryptedData = openssl_encrypt($data, $cipherMethod, $key, OPENSSL_RAW_DATA, $iv);
        return $encryptedData;
    }

    public static function decryptData($data, $key, $iv, $cipherMethod = "AES-256-CBC") {
        //global $cipherMethod, $key, $iv;
        $decryptedData = openssl_decrypt($data, $cipherMethod, $key, OPENSSL_RAW_DATA, $iv);
        return $decryptedData;
    }
}
?>