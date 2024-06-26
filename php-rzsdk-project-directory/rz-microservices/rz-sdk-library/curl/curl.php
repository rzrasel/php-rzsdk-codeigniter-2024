<?php
namespace RzSDK\Curl;
?>
<?php
class Curl {
    public $url = null;
    private $curl = null;

    public function __construct($baseUrl = null) {
        $this->curl = curl_init();
        $this->url = $baseUrl;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function exec($isPost = true, $postData = array()) {
        if($isPost) {
            // We POST the data
            curl_setopt($this->curl, CURLOPT_POST, true);
        } else {
            curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "GET");
        }
        // Set the url path we want to call
        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
        // Make it so the data coming back is put into a string
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        // Insert the data
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $postData);

        // You can also bunch the above commands into an array if you choose using: curl_setopt_array

        // Send the request
        $result = curl_exec($this->curl);

        // Get some cURL session information back
        $info = curl_getinfo($this->curl);
        //echo 'content type: ' . $info['content_type'] . '<br />';
        //echo 'http code: ' . $info['http_code'] . '<br />';
        $errorMessage = null;
        if (curl_errno($this->curl)) {
            $errorMessage = curl_error($this->curl);
        }
        // Free up the resources $curl is using
        curl_close($this->curl);

        return json_encode(array(
            "body"  => $result,
            "info"  => $info,
            "error" => $errorMessage,
        ));
    }
}
?>

<?php
    // Here is the data we will be sending to the service
    /*$postData = array(
        "message" => "Hello World", 
        "name" => "Anand"
    );*/
?>
