<?php
namespace App\Utils\Menu\Builder;
?>
<?php
use App\Utils\Menu\Builder\DataListToMenuBuilder;
use RzSDK\Log\DebugLog;
?>
<?php
class BuildHtmlMenu {
    public function buildTopbarMenu(array $dataList, string $baseUrl) {
        $menuBuilder = new DataListToMenuBuilder();
        //$htmlOutput = "<ul>";
        $htmlOutput = "<div class=\"sidebar\">";
        $htmlOutput .= "<ul class=\"menu\">";
        $htmlOutput .= $menuBuilder->build($dataList, function($key, $item, $path, $isParent) use($baseUrl) {
            if($isParent) {
                //return "<li>" . ucfirst($key) . "<ul>";  // Parent opens a submenu
                return "<li class=\"menu-item parent\"><span>" . ucfirst($key) . "</span>";
            } else if(!$isParent) {
                if(is_array($item)) {
                    return "";
                } else {
                    //return "<li><a href=\"{$baseUrl}{$path}\" target=\"_blank\">" . ucfirst($item) . "</a></li>";
                    $temp = explode("/", $path);
                    //DebugLog::log($temp);
                    $tempShift = array_shift($temp);
                    //DebugLog::log($temp);
                    if(is_array($temp)) {
                        $path = implode("/", $temp);
                    } else {
                        $path = $temp;
                    }
                    //DebugLog::log($path);
                    return "<li class=\"menu-item\"><a href=\"{$baseUrl}{$path}\">" . ucfirst($item) . "</a></li>";
                    //return "";
                }
            } else {
                return "</ul></li>"; // Closing parent submenu
            }
        });
        $htmlOutput .= "</ul></div>";
        return $htmlOutput;
    }
}
?>