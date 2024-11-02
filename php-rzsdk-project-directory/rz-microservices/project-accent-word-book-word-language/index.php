<?php
require_once("include.php");
?>
<?php
use RzSDK\URL\SiteUrl;
use RzSDK\Book\Navigation\Route\SideRouteNavigation;
use RzSDK\View\Html\View\MainHtmlView;
use RzSDK\Log\DebugLog;
?>
<?php
$baseUrl = SiteUrl::getBaseUrl();
$projectBaseUrl = $baseUrl;
//echo $baseUrl;
$sideNavigation = (new SideRouteNavigation($projectBaseUrl))->getSideNavigation();
//echo $sideNavigation;
//echo IdGenerator::getSysUserId();
?>
<?php
$str = "Hello PHP";
echo "Your string is".$str;
echo "<br>";
echo sha1($str);
?>
<?php
$pdfFile = "BanglaOvidhan.pdf";

function countPdfPages($pdfname) {
    $pdftext = file_get_contents($pdfname);
    $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);

    return $num;
}

$pdfname = $pdfFile; // Put your PDF path
//$pages = countPdfPages($pdfFile);

function getNumPagesPdf($filepath){
    $fp = @fopen(preg_replace("/\[(.*?)\]/i", "",$filepath),"r");
    $max=0;
    while(!feof($fp)) {
        $line = fgets($fp,255);
        if (preg_match('/\/Count [0-9]+/', $line, $matches)){
            preg_match('/[0-9]+/',$matches[0], $matches2);
            if ($max<$matches2[0]) $max=$matches2[0];
        }
    }
    fclose($fp);
    /*if($max==0){
        $im = new imagick($filepath);
        $max=$im->getNumberImages();
    }*/

    return $max;
}
/*$pages = getNumPagesPdf($pdfFile);
echo "PDF: {$pages}";*/
?>
<?php
$mainHtmlView = new MainHtmlView($projectBaseUrl);
$mainHtmlView->setPageTitle("Home")
    ->setCss()
    ->setSideNavigation($sideNavigation)
    ->setPageHeader("Home");
$mainHtmlView->renderTopView();
?>
<?php
$mainHtmlView->renderBotomView();
?>
<!--<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Home</title>
    <link href="$projectBaseUrl/css/style.css"  rel="stylesheet" type="text/css" charset="utf-8">
</head>
<body>
<table class="table-main-body-container">
    <tr>
        <td class="table-main-side-bar-menu">$sideNavigation</td>
        <td class="table-main-body-content-container">
            <table class="content-body-container">
                <tr>
                    <td>
                        <table class="table-entry-form-holder">
                            <tr>
                                <td class="table-entry-form-holder-page-header">Home</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>-->