<?php
require_once("include.php");
?>
<?php
use RzSDK\Authentication\Token\User\SecureTokenGenerator;
use RzSDK\Authentication\Token\User\SecureUserTokenGenerator;
use RzSDK\Encryption\McryptCipherIvGenerator;
use RzSDK\Encryption\JwtManager;
?>

<?php
$tokenId = uniqid("", true);
$timeInSeconds = 60 * 60 * 24;
$userAuthTokenList = array(
    "user_id" => "$-userId",
    "exp" => time() + $timeInSeconds, // Token expiration time (1 day)
    "iat" => time(), // Issued at time
    "jti" => $tokenId, // JWT ID
);
$secretKey = McryptCipherIvGenerator::opensslRandomIv();
$jwtManager = new JwtManager($secretKey);
$token = $jwtManager->createToken($userAuthTokenList);
$secretKey = rtrim(base64_encode($secretKey), "=");
echo "Generated Token: " . $token . PHP_EOL;
echo "Secret Key: " . $secretKey . PHP_EOL;
?>
<?php
$secureTokenGenerator = new SecureUserTokenGenerator("test");
//echo $secureTokenGenerator->generateToken("223");

$tokenGenerator = new SecureTokenGenerator(64);
$token = $tokenGenerator->generateToken();
echo "Generated Token: " . $token . PHP_EOL;
?>