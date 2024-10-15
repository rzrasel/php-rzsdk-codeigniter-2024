<?php
namespace RzSDK\Device;
?>
<?php
class ClientDevice {
    private $os = "os";
    private $device = "device";
    private $browser = "browser";
    private $httpAgent = "http_agent";

    public function device() {
        $userAgent = $_SERVER["HTTP_USER_AGENT"];
        $os_platform    = "UNKNOWN";
        $os_array       = array(
            "/android/i"            =>  "Android",
            "/blackberry/i"         =>  "BlackBerry",
            "/ipad/i"               =>  "iPad",
            "/iphone/i"             =>  "iPhone",
            "/ipod/i"               =>  "iPod",
            "/linux/i"              =>  "Linux",
            "/mac_powerpc/i"        =>  "Mac OS 9",
            "/macintosh|mac os x/i" =>  "Mac OS X",
            "/ubuntu/i"             =>  "Ubuntu",
            "/webos/i"              =>  "Mobile",
            "/win16/i"              =>  "Windows 3.11",
            "/win95/i"              =>  "Windows 95",
            "/win98/i"              =>  "Windows 98",
            "/windows me/i"         =>  "Windows ME",
            "/windows nt 5.0/i"     =>  "Windows 2000",
            "/windows nt 5.1/i"     =>  "Windows XP",
            "/windows nt 5.2/i"     =>  "Windows Server 2003/XP x64",
            "/windows nt 6.0/i"     =>  "Windows Vista",
            "/windows nt 6.1/i"     =>  "Windows 7",
            "/windows nt 6.2/i"     =>  "Windows 8",
            "/windows nt 6.3/i"     =>  "Windows 8.1",
            "/windows phone 8/i"    =>  "Windows Phone 8",
            "/windows phone os 7/i" =>  "Windows Phone 7",
            "/windows xp/i"         =>  "Windows XP",
            "/Windows nt 10.0/i"    =>  "Windows 10",
        );
        $found = false;
        //$addr = new RemoteAddress;
        $device = "";
        foreach ($os_array as $regex => $value) { 
            if($found)
                break;
            else if(preg_match($regex, $userAgent)) {
                $os_platform = $value;
                $device = preg_match("/(windows|mac|linux|ubuntu)/i", $os_platform, $result)
                ? "{$result[1]}" : (preg_match("/phone/i", $os_platform, $result) ? "{$result[1]}" : "UNKNOWN");
            }
        }
        $device = !$device? "UNKNOWN" : $device;
        return array(
            $this->os           => $os_platform,
            $this->device       => $device,
            $this->browser      => $this->browser(),
            $this->httpAgent    => $userAgent,
        );
    }

    public function getOs() {
        $detect = $this->device();
        return $detect[$this->os];
    }

    public function getDevice() {
        $detect = $this->device();
        return $detect[$this->device];
    }

    public function getBrowser() {
        $detect = $this->device();
        return $detect[$this->browser];
    }

    public function getHttpAgent() {
        $detect = $this->device();
        return $detect[$this->httpAgent];
    }

    public function browser() {
        $userAgent = $_SERVER["HTTP_USER_AGENT"];

        $browser        =   "UNKNOWN";

        $browser_array  = array(
            "/chrome/i"     =>  "Chrome",
            "/firefox/i"    =>  "Firefox",
            "/konqueror/i"  =>  "Konqueror",
            "/maxthon/i"    =>  "Maxthon",
            "/mobile/i"     =>  "Handheld Browser",
            "/msie/i"       =>  "Internet Explorer",
            "/netscape/i"   =>  "Netscape",
            "/opera/i"      =>  "Opera",
            "/safari/i"     =>  "Safari",
        );

        $found = false;

        foreach ($browser_array as $regex => $value) {
            /* if($found)
                break;
            //else if (preg_match($regex, $user_agent, $result)) {
            else if(preg_match($regex, $userAgent, $result)) {
                $browser = $value;
            } */

            if($found)
                break;
            else if(preg_match($regex, $userAgent, $result)) {
                $browser = $value;

                $regex = "/(Chrome|Safari|Firefox|Edge|Opera)/";
                $browser = preg_match($regex, $userAgent, $result) ? "{$result[0]}" : "UNKNOWN";
            }
        }
        return $browser;
    }
}
?>
<?php
//https://www.hackerearth.com/practice/notes/detecting-device-details-in-php
?>