<?php
namespace RzSDK\Encryption;
?>
    <?php
//defined("RZ_SDK_BASEPATH") OR exit("No direct script access allowed");
//defined("RZ_SDK_WRAPPER") OR exit("No direct script access allowed");
?>
<?php
enum CipherType: string {
    case AES_128_CTR    = "AES-128-CTR";
    case AES_256_CBC    = "AES-256-CBC";
}
?>