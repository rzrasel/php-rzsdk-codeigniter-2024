<?php
namespace RzSDK\Encryption;
?>
<?php
//defined("RZ_SDK_BASEPATH") OR exit("No direct script access allowed");
//defined("RZ_SDK_WRAPPER") OR exit("No direct script access allowed");
?>
<?php
enum EncryptionType: string {
    case JWT_TOKEN          = "jwt_token";
    case OPENSSL_ENCRYPTION = "openssl_encryption";
}
?>