<?php
namespace RzSDK\Validation;
?>
<?php
class EmailVerification {
    private $regex = "/^[a-z]+[a-z\'0-9]+([._-][a-z\'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$/";
    public $message = array();

    public function isRegexValidEmail($email) {
        return preg_match($this->regex, $email);
    }

    public function isValidEmailFilter($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match("/@.+\./", $email);
    }

    public function isCheckDnsrr($email) {
        list($alias, $domain) = explode("@", $email);
        return checkdnsrr($domain, "MX");
    }

    public function isValidEmail($email) {
        $this->message = array();
        if(empty($email)) {
            $this->message[] = "Email empty.";
            return false;
        }
        if(!$this->isRegexValidEmail($email)) {
            $this->message[] = "Email regex error.";
            return false;
        }
        if(!$this->isValidEmailFilter($email)) {
            $this->message[] = "Email validation error.";
            return false;
        }
        if(!$this->isCheckDnsrr($email)) {
            $this->message[] = "Email Dns error.";
            return false;
        }
        return true;
    }
}
?>