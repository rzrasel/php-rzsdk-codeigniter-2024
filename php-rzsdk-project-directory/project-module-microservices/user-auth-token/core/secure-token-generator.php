<?php
namespace RzSDK\Authentication\Token\User;
?>
<?php
class SecureTokenGenerator {
    private int $defaultLength;

    /**
     * Constructor to set default token length.
     * 
     * @param int $defaultLength Default token length (must be positive and even).
     */
    public function __construct(int $defaultLength = 64) {
        if ($defaultLength <= 0 || $defaultLength % 2 !== 0) {
            throw new InvalidArgumentException("Default token length must be a positive even integer.");
        }

        $this->defaultLength = $defaultLength;
    }

    /**
     * Generate a secure token.
     * 
     * @param int|null $length Optional custom length for the token.
     * @return string Generated secure token.
     */
    public function generateToken(?int $length = null): string {
        $length = $length ?? $this->defaultLength;

        if ($length <= 0 || $length % 2 !== 0) {
            throw new InvalidArgumentException("Token length must be a positive even integer.");
        }

        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Generate a secure token with expiration.
     * 
     * @param int $expirySeconds Expiry duration in seconds.
     * @return array Token data containing 'token' and 'expiry'.
     */
    public function generateTokenWithExpiry(int $expirySeconds): array {
        if ($expirySeconds <= 0) {
            throw new InvalidArgumentException("Expiry time must be a positive integer.");
        }

        $token = $this->generateToken();
        $expiry = time() + $expirySeconds;

        return [
            "token" => $token,
            "expiry" => $expiry,
        ];
    }
}
?>