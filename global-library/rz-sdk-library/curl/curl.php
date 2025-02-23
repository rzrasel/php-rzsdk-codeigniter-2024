<?php
namespace RzSDK\Curl;
?>
<?php
class Curl {
    private static ?Curl $instance = null;
    private $curl;
    private $url;
    private $options = [];
    private $headers = [];
    private $timeout = 30;

    private function __construct($baseUrl = null) {
        $this->curl = curl_init();
        $this->url = $baseUrl;
        $this->setDefaultOptions();
    }

    public static function getInstance($baseUrl = null): Curl {
        if(self::$instance === null) {
            self::$instance = new self($baseUrl);
        } else {
            self::$instance->setUrl($baseUrl);
        }
        return self::$instance;
    }

    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }

    public function setHeaders(array $headers) {
        $this->headers = $headers;
        return $this;
    }

    public function setOption($option, $value) {
        $this->options[$option] = $value;
        return $this;
    }

    public function setOptions(array $options) {
        $this->options = array_merge($this->options, $options);
        return $this;
    }

    public function exec($method = 'GET', $data = []) {
        // Set the URL
        curl_setopt($this->curl, CURLOPT_URL, $this->url);

        // Set the HTTP method
        $method = strtoupper($method);
        switch($method) {
            case 'POST':
                curl_setopt($this->curl, CURLOPT_POST, true);
                break;
            case 'PUT':
            case 'DELETE':
            case 'PATCH':
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);
                break;
            default:
                curl_setopt($this->curl, CURLOPT_HTTPGET, true);
                break;
        }

        // Set request data for POST, PUT, PATCH, etc.
        if(!empty($data)) {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        }

        // Set headers
        if(!empty($this->headers)) {
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
        }

        // Apply additional options
        foreach($this->options as $option => $value) {
            curl_setopt($this->curl, $option, $value);
        }

        // Execute the request
        $response = curl_exec($this->curl);

        // Handle errors
        $error = null;
        if (curl_errno($this->curl)) {
            $error = curl_error($this->curl);
        }

        // Get request info
        $info = curl_getinfo($this->curl);

        // Close the cURL handle
        curl_close($this->curl);

        // Return structured data
        return [
            'body'  => $response,
            'info'  => $info,
            'error' => $error,
        ];
    }

    private function setDefaultOptions() {
        $this->options = [
            CURLOPT_RETURNTRANSFER => true, // Return the response as a string
            CURLOPT_FOLLOWLOCATION => true, // Follow redirects
            CURLOPT_MAXREDIRS      => 10,   // Maximum number of redirects
            CURLOPT_TIMEOUT        => 30,   // Timeout in seconds
            CURLOPT_SSL_VERIFYPEER => false, // Disable SSL verification (for testing)
            CURLOPT_SSL_VERIFYHOST => false, // Disable SSL host verification (for testing)
            CURLOPT_USERAGENT      => $_SERVER['HTTP_USER_AGENT'] ?? 'RzSDK-Curl/1.0', // Set user agent
        ];
    }
}
?>
<?php
/*$curl = Curl::getInstance("https://api.example.com/data");
$response = $curl->exec('GET');
print_r($response);*/

/*$curl = \RzSDK\Curl\Curl::getInstance("https://api.example.com/data");
$curl->setHeaders([
    'Content-Type: application/json',
    'Authorization: Bearer YOUR_ACCESS_TOKEN'
]);
$data = json_encode(['name' => 'John Doe', 'email' => 'john@example.com']);
$response = $curl->exec('POST', $data);
print_r($response);*/
?>
