<?php
namespace RzSDK\Validation;
?>
<?php
class PasswordValidation {
    public function __construct() {
    }

    public static function isPassword($password) {
        //$regex = "/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/";
        //$regex = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.* )(?=.*[^a-zA-Z0-9]).{8,16}$/";
        $regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/";
        //$regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8, 48}$/";
        if(preg_match($regex, $password)) {
            return true;
        }
        return false;
    }

    public static function isPasswordWhiteSpace($password) {
        if(preg_match("/\s/", $password)) {
            return true;
        }
        return false;
    }
}
?>