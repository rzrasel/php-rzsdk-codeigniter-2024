<?php
namespace RzSDK\Authentication\Token\User;
?>
<?php

class SecureUserTokenGenerator {

    private $secretKey;

    public function __construct($secretKey) {
        $this->secretKey = $secretKey;
    }

    public function generateToken($userId, $timeInSeconds = 0) {
        // Create a unique identifier for the user
        $tokenId = uniqid("", true);
        if($timeInSeconds <= 0) {
            $timeInSeconds = 60 * 60 * 24;
        }

        // Create a payload with user information
        $payload = [
            "user_id" => $userId,
            "exp" => time() + $timeInSeconds, // Token expiration time (1 day)
            "iat" => time(), // Issued at time
            "jti" => $tokenId, // JWT ID
        ];

        // Encode the payload using a custom method
        $encodedPayload = json_encode($payload);
        $signature = hash_hmac("sha256", $encodedPayload, $this->secretKey);
        $tokenParts = [$encodedPayload, $signature];
        $token = implode(".", $tokenParts);

        return $token;
    }

    public function verifyToken($token) {
        try {
            // Decode the token
            $tokenParts = explode(".", $token);
            if (count($tokenParts) !== 2) {
                throw new Exception("Invalid token format");
            }

            $encodedPayload = $tokenParts[0];
            $signature = $tokenParts[1];

            // Verify the signature
            $expectedSignature = hash_hmac("sha256", $encodedPayload, $this->secretKey);
            if (!hash_equals($signature, $expectedSignature)) {
                throw new Exception("Invalid signature");
            }

            // Decode the payload
            $payload = json_decode($encodedPayload, true);

            // Check if the token is expired
            if ($payload["exp"] < time()) {
                throw new Exception("Token has expired");
            }

            // Return the user ID from the payload
            return $payload["user_id"];
        } catch (Exception $e) {
            // Handle token verification errors
            return false; 
        }
    }
}

?>