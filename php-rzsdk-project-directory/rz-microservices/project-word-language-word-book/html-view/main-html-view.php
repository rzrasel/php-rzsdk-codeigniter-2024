<?php
namespace RzSDK\View\Html\View;
?>
<?php
?>
<?php
class MainHtmlView {
    //
    private $projectBaseUrl;
    private $pageTitle;
    private $cssFile = array();
    private $sideNavigation;
    private $pageHeader;
    //

    public function __construct($projectBaseUrl) {
        $this->projectBaseUrl = $projectBaseUrl;
        $this->cssFile[] = $this->projectBaseUrl . "/css/style.css";
    }

    public function setPageTitle($pageTitle = "Home") {
        $this->pageTitle = $pageTitle;
        return $this;
    }

    public function setCss($css = "") {
        if(empty($css)) {
            return $this;
        }
        $css = trim($css, "\\");
        $css = trim($css, "/");
        $this->cssFile[] = $this->projectBaseUrl . $css;
        return $this;
    }

    public function setSideNavigation($sideNavigation) {
        $this->sideNavigation = $sideNavigation;
        return $this;
    }

    public function setPageHeader($pageHeader) {
        $this->pageHeader = $pageHeader;
        return $this;
    }

    public function renderTopView() {
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?= $this->pageTitle; ?></title>
<?php
foreach($this->cssFile as $css) {
?>
    <link href="<?= $css; ?>"  rel="stylesheet" type="text/css" charset="utf-8">
<?php
}
?>
</head>
<body>
<table class="table-main-body-container">
    <tr>
        <td class="table-main-side-bar-menu"><?= $this->sideNavigation; ?></td>
        <td class="table-main-body-content-container">
            <table class="content-body-container">
                <tr>
                    <td>
                        <table class="table-entry-form-holder">
                            <tr>
                                <td class="table-entry-form-holder-page-header"><?= $this->pageHeader; ?></td>
                            </tr>
                            <tr>
                                <td>
<?php
    }

    public function renderBotomView() {
?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
<?php
    }
}
?>
