<?php
namespace RzSDK\Encryption;
?>
<?php
/*defined("RZ_SDK_BASEPATH") OR exit("No direct script access allowed");
defined("RZ_SDK_WRAPPER") OR exit("No direct script access allowed");*/
?>
<?php
class PasswordEncryption {
    private int $defaultCost;

    public function __construct(int $defaultCost = 12) {
        $this->defaultCost = $defaultCost;
    }
    public function getPassword(string $password, int|string $algoType = PASSWORD_DEFAULT): string {
        return password_hash($password, $algoType, ['cost' => $this->defaultCost]);
    }

    public function getRehashedPassword(string $password, string $hash, int|string $algoType = PASSWORD_DEFAULT): ?string {
        if ($this->isPasswordVerified($password, $hash)) {
            if (password_needs_rehash($hash, $algoType, ['cost' => $this->defaultCost])) {
                return password_hash($password, $algoType, ['cost' => $this->defaultCost]);
            }
        }
        return null;
    }

    public function isPasswordVerified(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }
}
?>
<?php
/*class PasswordEncryption {
    function __construct() {
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
}*/
?>