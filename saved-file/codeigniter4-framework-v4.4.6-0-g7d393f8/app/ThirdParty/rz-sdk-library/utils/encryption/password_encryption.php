<?php
namespace RzSDK\Encryption;
defined("RZ_SDK_BASEPATH") OR exit("No direct script access allowed");
defined("RZ_SDK_WRAPPER") OR exit("No direct script access allowed");

class PasswordEncryption {
    function __construct() {
        /* echo __NAMESPACE__ . " ---- " . __CLASS__ . " ---- " . __METHOD__;
        echo "<br />"; */
    }

    public function getPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function getRehashedPassword($password, $hash) {
        if(password_verify($password, $hash)) {
            if(password_needs_rehash($hash, PASSWORD_DEFAULT, ['cost' => 12])) {
                return password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
            }
        }
        return null;
    }

    public function isPasswordVerified($password, $hash) {
        if(password_verify($password, $hash)) {
            return true;
        }

        return false;
    }
}