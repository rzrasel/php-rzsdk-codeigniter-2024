<?php
namespace RzSDK\Encryption;
?>
<?php
//defined("RZ_SDK_BASEPATH") OR exit("No direct script access allowed");
//defined("RZ_SDK_WRAPPER") OR exit("No direct script access allowed");
?>
<?php
class JwtManager {
    private $secretKey;

    public function __construct($secretKey) {
        $this->secretKey = $secretKey;
    }

    public function createToken($payload) {
        $header = [ 
            "alg" => "HS512",
            "typ" => "JWT"
        ];
        $base64UrlHeader = $this->base64UrlEncode(json_encode(["alg" => "HS256", "typ" => "JWT"]));
        $base64UrlPayload = $this->base64UrlEncode(json_encode($payload));
        $base64UrlSignature = hash_hmac("sha256", $base64UrlHeader . "." . $base64UrlPayload, $this->secretKey, true);
        $base64UrlSignature = $this->base64UrlEncode($base64UrlSignature);
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public function validateToken($token) {
        // Implementation for validating JWT
        list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = explode(".", $token);

        $signature = $this->base64UrlDecode($base64UrlSignature);
        $expectedSignature = hash_hmac("sha256", $base64UrlHeader . "." . $base64UrlPayload, $this->secretKey, true);

        return hash_equals($signature, $expectedSignature);
    }

    public function decodeToken($token) {
        // Implementation for decoding JWT
        list(, $base64UrlPayload, ) = explode(".", $token);
        $payload = $this->base64UrlDecode($base64UrlPayload);
        return json_decode($payload, true);
    }

    // Helper functions for base64 URL encoding/decoding
    private function base64UrlEncode($data) {
        $base64 = base64_encode($data);
        $base64Url = strtr($base64, "+/", "-_");
        return rtrim($base64Url, "=");
    }

    private function base64UrlDecode($data) {
        $base64 = strtr($data, "-_", "+/");
        $base64Padded = str_pad($base64, strlen($base64) % 4, "=", STR_PAD_RIGHT);
        return base64_decode($base64Padded);
    }
}
?>

<?php
/*
// Include the JwtManager class
require 'vendor/autoload.php';
use JwtManager;
// Your secret key (keep this secure)
$secretKey = 'your_secret_key';
// Create an instance of JwtManager
$jwtManager = new JwtManager($secretKey);
// Create a JWT
$payload = [
    "user_id" => 123,
    "username" => "rzrasel",
    "exp" => time() + 3600, // Token expiration time (1 hour)
];
$jwt = $jwtManager->createToken($payload);
echo "JWT Token: " . $jwt . PHP_EOL;
// Validate and decode the JWT
if ($jwtManager->validateToken($jwt)) {
    echo "JWT is valid." . PHP_EOL;
    $decodedPayload = $jwtManager->decodeToken($jwt);
    echo "Decoded Payload: " . json_encode($decodedPayload, JSON_PRETTY_PRINT);
} else {
    echo "JWT is invalid.";
}
?>

$now   = new \DateTimeImmutable();
$token = $config->builder()
    // // Configures the issuer (iss claim)
    ->issuedBy("http://example.com")
    // // Configures the audience (aud claim)
    ->permittedFor("ws://example.com")
    // Configures the id (jti claim)
    ->identifiedBy(uniqid('',TRUE))
    // Configures the time that the token was issue (iat claim)
    ->issuedAt($now)
    // Configures the expiration time of the token (exp claim)
    ->expiresAt($now->add(new DateInterval('PT' . $ttl . 'S')))
    // Configures a new claim, called "uid"
    ->withClaim('permissions', $permissions)
    // Builds a new token
    ->getToken($config->signer(), $config->signingKey());

return $token->toString();

<?php
// Online PHP compiler to run PHP program online
// Print "Try programiz.pro" message
echo uniqid("", TRUE);
echo "\n";
$key = openssl_random_pseudo_bytes(16);
echo $key;
echo "\n";
echo base64_encode($key);
echo "\n";

echo "\n";
$strong = uniqid("", TRUE);
$auth_key = openssl_random_pseudo_bytes(32, $strong);
//echo $auth_key;
// authentication
$enc_name = "test authentication";
$auth = hash_hmac('sha256', $enc_name, $auth_key, true);
$auth_enc_name = $auth . $enc_name;
echo $auth_enc_name;

// verification
$auth = substr($auth_enc_name, 0, 32);
$enc_name = substr($auth_enc_name, 32);
$actual_auth = hash_hmac('sha256', $enc_name, $auth_
key, true);

if (hash_equals($auth, $actual_auth)) {
    // perform decryption
    echo "perform decryption";
}
?>

<?php
// Define the data to encrypt
$data = "Hello world!";

// Define the secret key
$key = "secret";

// Define the encryption method
$method = "AES-256-CBC";

// Generate a random initialization vector (IV)
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));

// Encrypt the data
$encrypted = openssl_encrypt($data, $method, $key, 0, $iv);

// Concatenate the IV and the encrypted data
$encrypted = base64_encode($iv.$encrypted);

// Display the encrypted data
echo "Encrypted: ".$encrypted."\n";

// Decode the encrypted data
$encrypted = base64_decode($encrypted);

// Extract the IV and the encrypted data
$iv = substr($encrypted, 0, openssl_cipher_iv_length($method));
$encrypted = substr($encrypted, openssl_cipher_iv_length($method));

// Decrypt the data
$decrypted = openssl_decrypt($encrypted, $method, $key, 0, $iv);

// Display the decrypted data
echo "Decrypted: ".$decrypted."\n";
?>

<?php
// Generate a new pair of public and private keys
$keys = openssl_pkey_new(array(
    "private_key_bits" => 2048,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
));

// Extract the private key from the pair
openssl_pkey_export($keys, $private_key);

// Extract the public key from the pair
$public_key = openssl_pkey_get_details($keys)["key"];

// Display the public and private keys
echo "Public key: ".$public_key."\n";
echo "Private key: ".$private_key."\n";

// Define the data to encrypt
$data = "Hello world!";

// Encrypt the data with the public key
openssl_public_encrypt($data, $encrypted, $public_key);

// Encode the encrypted data with base64
$encrypted = base64_encode($encrypted);

// Display the encrypted data
echo "Encrypted: ".$encrypted."\n";

// Decode the encrypted data from base64
$encrypted = base64_decode($encrypted);

// Decrypt the data with the private key
openssl_private_decrypt($encrypted, $decrypted, $private_key);

// Display the decrypted data
echo "Decrypted: ".$decrypted."\n";
*/
?>