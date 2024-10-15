<?php
namespace RzSDK\Curl;
?>
<?php
use RzSDk\Curl\BaseCurl;
?>
<?php
//https://github.com/php-curl-class/php-curl-class
class Curl extends BaseCurl {
    public $url = null;
    private $curl = null;

    public $curlError = false;
    public $curlErrorCode = 0;
    public $curlErrorMessage = null;

    public $response = null;
    public $rawResponse = null;
    private $headers = [];

    public function __construct($baseUrl = null, $options = []) {
        if (!extension_loaded('curl')) {
            throw new \ErrorException('cURL library is not loaded');
        }
        $this->curl = curl_init();
        $this->initialize($baseUrl, $options);
    }

    private function initialize($base_url = null, $options = []) {
        if (isset($options)) {
            $this->setOpts($options);
        }

        $this->setOptInternal(CURLOPT_RETURNTRANSFER, true);

        if ($base_url !== null) {
            $this->setUrl($base_url);
        }
    }

    public function setUrl($url, $mixed_data = "") {
        $this->url = $url;
        $this->setOpt(CURLOPT_URL, $this->url);
    }

    protected function setOptInternal($option, $value) {
        $success = curl_setopt($this->curl, $option, $value);
        if ($success) {
            $this->options[$option] = $value;
        }
        return $success;
    }

    public function setOpt($option, $value) {
        $success = curl_setopt($this->curl, $option, $value);
        if ($success) {
            $this->options[$option] = $value;
            $this->userSetOptions[$option] = $value;
        }
        return $success;
    }

    public function setOpts($options) {
        if (!count($options)) {
            return true;
        }
        foreach ($options as $option => $value) {
            if (!$this->setOpt($option, $value)) {
                return false;
            }
        }
        return true;
    }

    public function exec($ch = null) {
        $this->rawResponse = curl_exec($this->curl);
        $this->curlErrorCode = curl_errno($this->curl);
        $this->curlErrorMessage = curl_error($this->curl);
        $this->curlError = $this->curlErrorCode !== 0;
        if ($this->curlError) {
            $curl_error_message = curl_strerror($this->curlErrorCode);

            /* if ($this->curlErrorCodeConstant !== '') {
                $curl_error_message .= ' (' . $this->curlErrorCodeConstant . ')';
            } */

            if (!empty($this->curlErrorMessage)) {
                $curl_error_message .= ': ' . $this->curlErrorMessage;
            }

            $this->curlErrorMessage = $curl_error_message;
        }

        //$this->response = $this->parseResponse($this->responseHeaders, $this->rawResponse);
        $this->response = $this->rawResponse;
        //$this->unsetHeader('Content-Length');
        $this->setOptInternal(CURLOPT_NOBODY, false);
    }

    public function setHeader($key, $value) {
        $this->headers[$key] = $value;
        $headers = [];
        foreach ($this->headers as $key => $value) {
            $headers[] = $key . ': ' . $value;
        }
        $this->setOpt(CURLOPT_HTTPHEADER, $headers);
    }

    public function setHeaders($headers) {
        if (ArrayUtil::isArrayAssoc($headers)) {
            foreach ($headers as $key => $value) {
                $key = trim($key);
                $value = trim($value);
                $this->headers[$key] = $value;
            }
        } else {
            foreach ($headers as $header) {
                list($key, $value) = explode(':', $header, 2);
                $key = trim($key);
                $value = trim($value);
                $this->headers[$key] = $value;
            }
        }

        $headers = [];
        foreach ($this->headers as $key => $value) {
            $headers[] = $key . ': ' . $value;
        }

        $this->setOpt(CURLOPT_HTTPHEADER, $headers);
    }

    public function head($url, $data = []) {
        if (is_array($url)) {
            $data = $url;
            $url = (string)$this->url;
        }
        $this->setUrl($url, $data);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, "HEAD");
        $this->setOpt(CURLOPT_NOBODY, true);
        return $this->exec();
    }

    public function get($url, $data = []) {
        $this->setUrl($url, $data);
        $this->setOptInternal(CURLOPT_CUSTOMREQUEST, "GET");
        $this->setOptInternal(CURLOPT_HTTPGET, true);
        return $this->exec();
    }

    public function options($url, $data = []) {
        if (is_array($url)) {
            $data = $url;
            $url = (string)$this->url;
        }
        $this->setUrl($url, $data);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, "OPTIONS");
        return $this->exec();
    }

    public function patch($url, $data = []) {
        if (is_array($url)) {
            $data = $url;
            $url = (string)$this->url;
        }

        /* if (is_array($data) && empty($data)) {
            $this->removeHeader('Content-Length');
        } */

        $this->setUrl($url);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, "PATCH");
        //$this->setOpt(CURLOPT_POSTFIELDS, $this->buildPostData($data));
        $this->setOpt(CURLOPT_POSTFIELDS, http_build_query($data));
        return $this->exec();
    }

    public function post($url, $data = "", $follow_303_with_post = false) {
        if (is_array($url)) {
            $follow_303_with_post = (bool)$data;
            $data = $url;
            $url = (string)$this->url;
        }

        $this->setUrl($url);

        // Set the request method to "POST" when following a 303 redirect with
        // an additional POST request is desired. This is equivalent to setting
        // the -X, --request command line option where curl won't change the
        // request method according to the HTTP 30x response code.
        if ($follow_303_with_post) {
            $this->setOpt(CURLOPT_CUSTOMREQUEST, "POST");
        } elseif (isset($this->options[CURLOPT_CUSTOMREQUEST])) {
            // Unset the CURLOPT_CUSTOMREQUEST option so that curl does not use
            // a POST request after a post/redirect/get redirection. Without
            // this, curl will use the method string specified for all requests.
            $this->setOpt(CURLOPT_CUSTOMREQUEST, null);
        }

        $this->setOpt(CURLOPT_POST, true);
        //$this->setOpt(CURLOPT_POSTFIELDS, $this->buildPostData($data));
        $this->setOpt(CURLOPT_POSTFIELDS, http_build_query($data));
        return $this->exec();
    }

    public function put($url, $data = []) {
        if (is_array($url)) {
            $data = $url;
            $url = (string)$this->url;
        }
        $this->setUrl($url);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, "PUT");
        //$put_data = $this->buildPostData($data);
        $put_data = http_build_query($data);
        /* if (empty($this->options[CURLOPT_INFILE]) && empty($this->options[CURLOPT_INFILESIZE])) {
            if (is_string($put_data)) {
                $this->setHeader('Content-Length', strlen($put_data));
            }
        } */
        if (!empty($put_data)) {
            $this->setOpt(CURLOPT_POSTFIELDS, $put_data);
        }
        return $this->exec();
    }

    public function close() {
        if (is_resource($this->curl) || $this->curl instanceof \CurlHandle) {
            curl_close($this->curl);
        }
        $this->curl = null;
        $this->options = null;
        $this->userSetOptions = null;
        /* $this->jsonDecoder = null;
        $this->jsonDecoderArgs = null;
        $this->xmlDecoder = null;
        $this->xmlDecoderArgs = null;
        $this->headerCallbackData = null;
        $this->defaultDecoder = null; */
    }

    public function reset() {
        if (is_resource($this->curl) || $this->curl instanceof \CurlHandle) {
            curl_reset($this->curl);
        } else {
            $this->curl = curl_init();
        }

        /* $this->setDefaultUserAgentInternal();
        $this->setDefaultTimeoutInternal();
        $this->setDefaultHeaderOutInternal(); */

        $this->initialize();
    }
}
?>