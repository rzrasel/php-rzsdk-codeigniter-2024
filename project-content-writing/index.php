<?php
require_once("utils/include.php");
?>
<?php
use RzSDK\URL\SiteUrl;
use RzSDK\Site\Link\SiteLinkUtils;
?>
<?php
$baseUrl = SiteUrl::getBaseUrl();
$siteLinkUtils = new SiteLinkUtils($baseUrl);
$siteLink = $siteLinkUtils->getSiteLink();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!--<meta http-equiv="Content-Security-Policy" content="default-src 'none'; object-src 'none'; script-src resource: chrome:; connect-src https:; img-src https: data: blob: chrome:; style-src 'unsafe-inline';" />-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Content Writing</title>
    <link href="<?= $baseUrl; ?>/css/style.css"  rel="stylesheet" type="text/css" charset="utf-8">
</head>
<body>
<table cellspacing="0" cellpadding="0" width="100%" class="main-body-table">
    <tr>
        <td class="main-side-bar-menu"><?= $siteLink; ?></td>
        <td class="main-body-container">
            <table cellspacing="0" cellpadding="0" class="content-body-container">
                <tr>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
    <!--<div class="div-body-table">
        <div class="div-body-table-lef"><?php /*= $siteLink; */?></div>
        <div class="div-body-table-right"></div>
    </div>-->
</body>
</html>

<!--https://cssdeck.com/labs/pure-css3-expand-collapse-->
<!--<ul>
    <li><input type="checkbox" id="cb1"/><label for="cb1">Level 1</label>
        <ul>
            <li><input type="checkbox" id="cb2"/><label for="cb2">Level 2-A</label>
                <ul>
                    <li><label>Level 3-A</label> Content</li>
                    <li><label>Level 3-B</label> Content</li>
                </ul>
            </li>
            <li><input type="checkbox" id="cb3"/><label for="cb3">Level 2-B</label>
                <ul>
                    <li><input type="checkbox" id="cb5"/><label for="cb5">Level 3-A</label>
                        <ul>
                            <li><label>Level 4-A</label> Content</li>
                            <li><label>Level 4-B</label> Content</li>
                            <li><label>Level 4-C</label> Content</li>
                            <li><label>Level 4-D</label> Content</li>
                        </ul>
                    </li>
                    <li><input type="checkbox" id="cb6"/><label for="cb6">Level 3-B</label>
                        <ul>
                            <li><label>Level 4-A</label> Content</li>
                            <li><label>Level 4-B</label> Content</li>
                        </ul>
                    </li>
                    <li><input type="checkbox" id="cb7"/><label for="cb7">Level 3-C</label>
                        <ul>
                            <li><label>Level 4-A</label> Content</li>
                            <li><label>Level 4-B</label> Content</li>
                            <li><label>Level 4-C</label> Content</li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li><input type="checkbox" id="cb4"/><label for="cb4">Level 2-C</label>
                <ul>
                    <li><label>Level 3-A</label> Content</li>
                </ul>
            </li>
        </ul>
    </li>
</ul>

ul {
    list-style-type: none;
}
label{
    background-color: #AAAFAB;
    border-radius: 5px;
    padding: 3px;
    padding-left: 25px;
    color: white;
}
li {
    margin: 10px;
    padding: 5px;
    border: 1px solid #ABC;
    border-radius: 5px;
}
input[type=checkbox] {
    display: none;
}
input[type=checkbox] ~ ul {
    max-height: 0;
    max-width: 0;
    opacity: 0;
    overflow: hidden;
    white-space:nowrap;
    -webkit-transition:all 1s ease;
    -moz-transition:all 1s ease;
    -o-transition:all 1s ease;
    transition:all 1s ease;

}
input[type=checkbox]:checked ~ ul {
    max-height: 100%;
    max-width: 100%;
    opacity: 1;
}
input[type=checkbox] + label:before{
    transform-origin:25% 50%;
    border: 8px solid transparent;
    border-width: 8px 12px;
    border-left-color: white;
    margin-left: -20px;
    width: 0;
    height: 0;
    display: inline-block;
    text-align: center;
    content: '';
    color: #AAAFAB;
    -webkit-transition:all .5s ease;
    -moz-transition:all .5s ease;
    -o-transition:all .5s ease;
    transition:all .5s ease;
    position: absolute;
    margin-top: 1px;
}
input[type=checkbox]:checked + label:before {
    transform: rotate(90deg);
    /*margin-top: 6px;
    margin-left: -25px;*/
}-->