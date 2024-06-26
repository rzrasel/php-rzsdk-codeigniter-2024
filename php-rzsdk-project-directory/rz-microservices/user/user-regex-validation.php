<?php
namespace RzSDK\User;
?>
<?php
require_once("../include.php");
require_once("include.php");
?>
<?php
use RzSDK\Response\Response;
use RzSDK\Response\Info;
use RzSDK\Response\InfoType;
use RzSDK\Model\User\UserRegistrationRequestModel;
?>
<?php
class UserRegistrationRegexValidation {
    public function __construct() {
        //$this->execute();
    }

    public function execute(UserRegistrationRequestModel $userRegiRequestModel) {
        if(empty($userRegiRequestModel->email)) {
            $this->response($_POST, new Info("Email address can not be empty", InfoType::ERROR));
            return false;
        }
        if(!$this->isEmail($userRegiRequestModel->email)) {
            $this->response($_POST, new Info("Invalid email address", InfoType::ERROR));
            return false;
        }
        if(empty($userRegiRequestModel->password)) {
            $this->response($_POST, new Info("Password can not be empty", InfoType::ERROR));
            return false;
        }
        if(!$this->isPassword($userRegiRequestModel->password)) {
            $this->response($_POST, new Info("Invalid password. Password length in btween 8 to 40, containing at least one lowercase letter, uppercase letter, number and #?!@$%^&*- character", InfoType::ERROR));
            return false;
        }
        if($this->isPasswordWhiteSpace($userRegiRequestModel->password)) {
            $this->response($_POST, new Info("Invalid password, password should not contain any white space", InfoType::ERROR));
            return false;
        }
        if(strlen($userRegiRequestModel->password) > 40) {
            $this->response($_POST, new Info("Invalid password, password length can not more than 40 character", InfoType::ERROR));
            return false;
        }
        return true;
    }

    private function isEmail($email) {
        //$regex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/";
        $regex = "/^[a-z][_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/";
        if(preg_match($regex, $email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    private function isPassword($password) {
        //$regex = "/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/";
        //$regex = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.* )(?=.*[^a-zA-Z0-9]).{8,16}$/";
        $regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/";
        //$regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8, 48}$/";
        if(preg_match($regex, $password)) {
            return true;
        }
        return false;
    }

    private function isPasswordWhiteSpace($password) {
        if(preg_match("/\s/", $password)) {
            return true;
        }
        return false;
    }

    private function response($body, Info $info) {
        $response = new Response();
        $response->body = $body;
        $response->info = $info;
        echo $response->toJson();
    }
}
?>