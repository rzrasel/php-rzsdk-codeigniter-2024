<?php
namespace RzSDK\Word\Entry;
?>
<?php
require_once("include.php");
?>
<?php
use RzSDK\URL\SiteUrl;
use RzSDK\User\Login\Test;
use RzSDK\Word\Navigation\SideLink;
use RzSDK\Data\Entry\Activity\LanguageEntryActivity;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Utils\Alert\Message\AlertMessageBox;
use RzSDK\Log\DebugLog;
?>
<?php
//$languageEntryActivity = new LanguageEntryActivity($_POST);
?>
<?php
class LanguageEntry {
    public ServiceListener $serviceListener;
    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        //$this->generateUI();
    }

    public function execute() {
        if(empty($_POST)) {
            return;
        }
        (new LanguageEntryActivity(
            new class($this) implements ServiceListener {
                private LanguageEntry $outerInstance;

                // Constructor to receive outer instance
                public function __construct($outerInstance) {
                    $this->outerInstance = $outerInstance;
                }

                public function onError($dataSet, $message) {
                    //DebugLog::log($dataSet);
                    //DebugLog::log($message);
                    $this->outerInstance->serviceListener->onError($dataSet, $message);
                    //showAlertMessage($message);
                }

                function onSuccess($dataSet, $message) {
                    //DebugLog::log($message);
                    $this->outerInstance->serviceListener->onSuccess($dataSet, $message);
                    //showAlertMessage($message);
                }
            }
        ))
            ->execute($_POST);
    }
}
//(new LanguageEntry())->execute();
?>
<?php
//(new LanguageEntry())->execute();
//new LanguageEntry();
//<button class="_42ft _5upp _50zy clear uiTypeaheadCloseButton _50-0 _50z-" title="Remove" onclick="" type="button" id="u_0_bj">Remove</button><input autocomplete="off" class="hiddenInput" type="hidden">
?>
<?php
$baseUrl = SiteUrl::getBaseUrl();
$projectBaseUrl = rtrim(dirname($baseUrl), "/");
//echo $baseUrl;
$sideLink = (new SideLink($projectBaseUrl))->getSideLink();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Language Entry</title>
    <link href="<?= $projectBaseUrl; ?>/css/style.css"  rel="stylesheet" type="text/css" charset="utf-8">
</head>
<body>
<table class="table-main-body-container">
    <tr>
        <td class="table-main-side-bar-menu"><?= $sideLink; ?></td>
        <td class="table-main-body-content-container">
            <table class="table-entry-form-holder">
                <tr>
                    <td>
                        <!--Body Container Table Start-->
                        <table class="table-entry-form-holder">
                            <tr>
                                <td class="table-entry-form-holder-page-header">Language Entry Form</td>
                            </tr>
                            <tr>
                                <td class="response-message-section">
                                    <div class="response-message-box">
<?php
(new LanguageEntry(
    new class implements ServiceListener {
        private AlertMessageBox $alertMessageBox;
        public function __construct() {
            $this->alertMessageBox = new AlertMessageBox();
        }
        public function onError($dataSet, $message) {
            //DebugLog::log($dataSet);
            //DebugLog::log($message);
            echo $this->alertMessageBox->build($message);
        }
        function onSuccess($dataSet, $message) {
            //DebugLog::log($message);
            echo $this->alertMessageBox->build($message);
        }
    }
))->execute();
?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="POST">
                                        <table class="table-entry-form-field-container">
                                            <tr>
                                                <td class="table-entry-form-field-left">Language: </td>
                                                <td class="table-entry-form-field-right">
                                                    <input type="text" name="language" value="" placeholder="Language Name" required="required" />
                                                </td>
                                            </tr>
                                            <tr><td></td><td height="30px"></td></tr>
                                            <!--<tr>
                                                <td></td><td><input type="button" value="Submit" /></td>
                                            </tr>-->
                                            <tr>
                                                <td></td>
                                                <td class="form-button"><button class="button-6" type="submit">Submit</button></td>
                                            </tr>
                                        </table>
                                    </form>
                                </td>
                            </tr>
                        </table>
                        <!--Body Container Table End-->
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>