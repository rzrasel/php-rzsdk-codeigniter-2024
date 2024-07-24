<?php
namespace RzSDK\Site\Link;
?>
<?php
class SiteLinkUtils {
    private $baseUrl;

    public function __construct($baseUrl = "") {
        $this->baseUrl = $baseUrl;
    }

    public function getSiteLink() {
        $siteLinkData = $this->getLinkData();
        //$recurseSiteLink = $this->recurseSiteLink("", $siteLinkData);
        $recurseSiteLink = $this->recurseSiteLink("", $siteLinkData);
        $recurseSiteLink = "\n<ul id=\"main-drop-down-menu-id\" class=\"main-drop-down-menu\">\n{$recurseSiteLink}</ul>\n";
        return $recurseSiteLink;
    }

    private function recurseSiteLink($data, $dataList = array(), $count = 0) {
        foreach($dataList as $key => $value) {
            if(is_array($value)) {
                $count++;
                $data .= "<li id=\"not-href-menu\" class=\"\"><label>{$key}</label>\n";
                $data .= "<ul>\n";
                $tempResult = $this->recurseSiteLink($data, $value);
                $data = $tempResult;
                $data .= "</ul>\n";
                $data .= "</li>\n";
            } else {
                $data .= "<li><a href=\"{$key}?page-title={$value}\" >{$value}</a></li>\n";
            }
        }
        return $data;
    }

    private function recurseSiteLinkOld3($data, $dataList = array(), $count = 0) {
        foreach($dataList as $key => $value) {
            if(is_array($value)) {
                $count++;
                $data .= "<li id=\"not-href-menu\" class=\"\"><input type=\"checkbox\" id=\"cb{$count}\"/><label  for=\"cb{$count}\"><a>{$key}</a></label>\n";
                $data .= "<ul>\n";
                $tempResult = $this->recurseSiteLink($data, $value);
                $data = $tempResult;
                $data .= "</ul>\n";
                $data .= "</li>\n";
            } else {
                $data .= "<li><a href=\"{$key}\" >{$value}</a></li>\n";
            }
        }
        return $data;
    }

    private function recurseSiteLinkOld2($data, $dataList = array()) {
        foreach($dataList as $key => $value) {
            if(is_array($value)) {
                $data .= "<li id=\"not-href-menu\" class=\"\"><a>{$key}</a></li>\n";
                $data .= "<ul>\n";
                $tempResult = $this->recurseSiteLink($data, $value);
                $data = $tempResult;
                $data .= "</ul>\n";
            } else {
                $data .= "<li><a href=\"{$key}\" >{$value}</a></li>\n";
            }
        }
        return $data;
    }

    private function recurseSiteLinkOld1($siteLink, $linkDataSet = array(), &$results = array()) {
        $siteLink .= "<ul class=\"upper-foreach\">\n";
        foreach($linkDataSet as $key => $value) {
            if(is_array($value)) {
                $siteLink .= "<ul class=\"foreach-key\">\n";
                $siteLink .= "<li>{$key}</li>\n";
                //$siteLink .= "<ul>\n";
                $retVal = $this->recurseSiteLink($siteLink, $value);
                //$results[] = $retVal;
                $siteLink = $retVal . "\n";
                //$siteLink .= "</ul>\n";
                $siteLink .= "</ul class=\"foreach-key\">\n";
                //echo $retVal;
            } else {
                $siteLink .= "<li><a href=\"{$key}\" >{$value}</a></li>\n";
            }
        }
        $siteLink .= "</ul class=\"upper-foreach\">\n";
        return $siteLink;
    }

    public function getLinkData() {
        return array(
            $this->baseUrl => "Home",
            "Thinking Topics" => array(
                $this->baseUrl . "/thinking-topics/thinking-topics-1.php" => "Thinking Topics (Page 1)",
            ),
            "Nalanda University - নালন্দা ইউনিভার্সিটি" => array(
                $this->baseUrl . "/nalanda-university/nalanda-university-page-01.php" => "Nalanda University (Page 1) - নালন্দা ইউনিভার্সিটি",
                $this->baseUrl . "/nalanda-university/nalanda-university-page-10.php" => "Nalanda University (Page 10) - নালন্দা ইউনিভার্সিটি",
            ),
            "Tongue Of The Freedom Fighter - মুক্তিযোদ্ধার বয়ানে" => array(
                //$this->baseUrl . "/freedom-speech" => "Freedom Speech",
                $this->baseUrl . "/tongue-of-the-freedom-fighter/tongue-of-the-freedom-fighter-page-01.php" => "Tongue Of The Freedom Fighter (Page 1) - মুক্তিযোদ্ধার বয়ানে",
                $this->baseUrl . "/tongue-of-the-freedom-fighter/tongue-of-the-freedom-fighter-page-99.php" => "Tongue Of The Freedom Fighter (Page 99) - মুক্তিযোদ্ধার বয়ানে",
            ),
        );
    }
}
?>
