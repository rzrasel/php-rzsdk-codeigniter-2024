<?php
namespace RzSDK\Validation;
?>
<?php
/**
 * SmtpEmailVerification exception handler 
 */
class SmtpEmailVerificationException extends \Exception {
    /**
     * Prettify error message output
     * @return string
     */
    public function errorMessage() {
        $errorMsg = $this->getMessage();
        return $errorMsg;
    }
}
?>