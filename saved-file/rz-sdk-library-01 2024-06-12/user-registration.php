<?php
namespace RzSDK\User\Registration;
require_once("run-autoloader.php");
require_once("user-registration/user-registration-process.php");
?>
<?php
use \RzSDK\Model\User\Registration;
use \RzSDK\User\Registration\UserRegistrationProcess;
use RzSDK\Validation\EmailVerification;
use RzSDK\Validation\SmtpEmailVerification;
?>
<?php
class UserRegistration {
    public function __construct() {
        //
    }

    public function registrationByEmail($email, $password) {
        $emailVerification = new EmailVerification();
        if($emailVerification->isValidEmail($email)) {
            // Initialize library class
            $smtpEmailVerification = new SmtpEmailVerification();
            // Set the timeout value on stream
            $smtpEmailVerification->setStreamTimeoutWait(20);
            // Set email address for SMTP request
            $smtpEmailVerification->setEmailFrom("from@email.com");
            if($smtpEmailVerification->check($email)) {
                echo "Email &lt;" . $email . "&gt; is exist!";
            } else {
                echo "Email &lt;" . $email . "&gt; is valid, but not exist!";
            }
        } else {
            echo "error";
        }
    }
}
?>
<?php
$userRegistration = new UserRegistration();
$userRegistration->registrationByEmail("w2332.frr@gmaiol.com", "");
?>
<?php
/* $userRegistration = new UserRegistrationProcess("database/user-registration.sqlite");
$userRegistration->insert();
$items = $userRegistration->getData();
foreach($items as $item) {
    echo "{$item->userId} {$item->modifiedDate} {$item->createdDate}<br />";
} */
?>
<!--
-->