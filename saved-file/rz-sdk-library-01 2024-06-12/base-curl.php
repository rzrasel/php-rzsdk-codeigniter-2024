<?php
namespace RzSDK\Curl;
?>
<?php
abstract class BaseCurl {
    protected $options = [];
    protected $userSetOptions = [];

    abstract public function setOpt($option, $value);
    protected function setOptInternal($option, $value) {
    }
    abstract public function setOpts($options);
    abstract public function setHeader($key, $value);
    abstract public function setHeaders($headers);

    public function getOpt($option) {
        return $this->options[$option] ?? null;
    }

    public function setAutoReferrer($auto_referrer = true) {
        $this->setOpt(CURLOPT_AUTOREFERER, $auto_referrer);
    }

    public function setBasicAuthentication($username, $password = "") {
        $this->setOpt(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $this->setOpt(CURLOPT_USERPWD, $username . ":" . $password);
    }

    public function setConnectTimeout($seconds) {
        $this->setOpt(CURLOPT_CONNECTTIMEOUT, $seconds);
    }

    public function setFile($file) {
        $this->setOpt(CURLOPT_FILE, $file);
    }

    protected function setFileInternal($file) {
        $this->setOptInternal(CURLOPT_FILE, $file);
    }

    public function setFollowLocation($follow_location = true) {
        $this->setOpt(CURLOPT_FOLLOWLOCATION, $follow_location);
    }

    public function setForbidReuse($forbid_reuse = true) {
        $this->setOpt(CURLOPT_FORBID_REUSE, $forbid_reuse);
    }

    public function curlSetup($url, $parameters) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://stackoverflow.com/questions/17230246/php-curl-get-request-and-requests-body");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        /* curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields)); */
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);
        //curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        //curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:11.0) Gecko/20100101 Firefox/11.0");
        //curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
        $response = curl_exec($curl);
        $error = curl_error($curl);
        $errno = curl_errno($curl);
        curl_close($curl);
        echo $response . " error " . $error . " error no " . $errno;
    }
}
?>
<?php
/* $baseCurl = new BaseCurl();
// $baseCurl->curlSetup("", "");
$baseCurl->get("https://stackoverflow.com/questions/17230246/php-curl-get-request-and-requests-body");
echo $baseCurl->response; */
?>