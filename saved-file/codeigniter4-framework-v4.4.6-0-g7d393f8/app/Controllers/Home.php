<?php

namespace App\Controllers;
use App\Libraries\TestSDKLibrary;
use App\Libraries\EncryptionSDKLibrary;
use RzSDK\Encryption\PasswordEncryption;
use RzSDK\DateTime\DateTime;
use RzSDK\DateTime\DateDiffType;
//use Encryption\Password\PasswordEncryption;
// require_once APPPATH . "/Libraries/test-sdk-library.php";
use RzSDK\Database\SqliteConnection;
use RzSDK\User\UserRegistration;

class Home extends BaseController
{
    public function index(): string
    {
        //echo "Welcome";
        //$template = new TestSDKLibrary();
        $template = new EncryptionSDKLibrary();
        //
        echo DateTime::getCurrentDateTime();
        echo "<br />";
        echo DateTime::getIntervalInSeconds("2024-02-26 13:38:20", "2024-02-26 13:34:10");
        echo "<br />";
        echo DateTime::getDifferent("2024-02-26 13:38:20", "2024-02-26 13:34:10", dateDiffType: DateDiffType::minutes);
        echo "<br />";
        echo DateTime::addDateTime("2024-02-26 13:38:20", 0, DateDiffType::hours);
        echo "<br />";
        echo DateTime::subtractDateTime("2024-02-26 13:38:20", 100, DateDiffType::hours);
        echo "<br />";
        echo DateTime::getMicrotime();
        echo "<br />";
        echo strlen(DateTime::getMicrotime());
        echo "<br />";
        echo time();
        echo "<br />";
        echo strlen(time());
        echo "<br />";
        echo DateTime::getMicroToDate(DateTime::getMicrotime());
        echo "<br />";
        //
        $password = new PasswordEncryption();
        echo $password->getPassword("KJDlkjlkjasdjfls");
        echo "<br />";
        echo $password->isPasswordVerified("KJDlkjlkjasdjfls", "\$2y\$10\$h08BME.FLGeHR9sLDi1RW.tCduBwNkr6AIXYo6BXXzIcPWk2pHfO2");
        echo "<br />";
        echo $password->getRehashedPassword("KJDlkjlkjasdjfls", "\$2y\$10\$h08BME.FLGeHR9sLDi1RW.tCduBwNkr6AIXYo6BXXzIcPWk2pHfO2");
        echo "<br />";
        $sqlConn = new SqliteConnection(SQLITE_DBPATH);
        new UserRegistration($sqlConn);
        return view('welcome_message');
    }
}
