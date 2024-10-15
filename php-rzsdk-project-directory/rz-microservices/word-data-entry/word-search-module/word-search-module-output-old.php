<?php
namespace RzSDK\Dictionary\Search\Module;
?>
<?php
require_once("include.php");
?>
<?php
use RzSDK\URL\SiteUrl;
use RzSDK\Word\Navigation\SideLink;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Model\HTTP\Request\Search\HttpWordSearchModuleRequestModel;
use RzSDK\Service\Dictionary\Search\Module\WordSearchModuleService;
use RzSDK\Model\HTTP\Response\Search\HttpWordSearchModuleResponseModel;
use RzSDK\Dictionary\Search\Module\WordSearchModule;
use RzSDK\Utils\Database\Options\Language\DatabaseLanguageOptions;
use RzSDK\Log\DebugLog;
?>
<?php
class CallerClass {
    public $responseData;
    private $defaultLanguageName = "English";
    public function __construct() {
        $languageId = (new DatabaseLanguageOptions())
            ->getLanguageIdByName($this->defaultLanguageName);
        $_POST["word_language"] = $languageId;
        $_POST["search_word"] = "";
        (new WordSearchModule(
            new class($this) implements ServiceListener {
                private CallerClass $outerInstance;

                // Constructor to receive outer instance
                public function __construct($outerInstance) {
                    $this->outerInstance = $outerInstance;
                }

                public function onError($dataSet, $message) {
                    //DebugLog::log($dataSet);
                    //DebugLog::log($message);
                    $this->outerInstance->responseData = $message;
                }

                function onSuccess($dataSet, $message) {
                    //DebugLog::log($dataSet);
                    //DebugLog::log($message);
                    $this->outerInstance->responseData = $dataSet->data;
                    //DebugLog::log($this->outerInstance->responseData);
                }
            }
        ))
            ->setWordLinkUrl("")
            ->execute($_POST);
    }
}
$callerClass = new CallerClass();
?>
<?php
$baseUrl = SiteUrl::getBaseUrl();
$projectBaseUrl = rtrim(dirname($baseUrl), "/");
//echo $baseUrl;
$sideLink = (new SideLink($projectBaseUrl))->getSideLink();
?>
<?php
$leftWidth = 15;
$midWidth = 2;
$rightWidth = 100 - ($leftWidth + $midWidth);
?>
<!--<table width="100%">
    <thead>
        <tr style="cursor: default;">
            <th>Word</th>
            <th>Pronunciation</th>
            <th>Parts of Speech</th>
            <th>Syllable</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Word</td>
            <td>Pronunciation</td>
            <td>PartsOfSpeech</td>
            <td>Syllable</td>
        </tr>
    </tbody>
</table>-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Word Search Module</title>
    <link href="<?= $projectBaseUrl; ?>/css/style.css"  rel="stylesheet" type="text/css" charset="utf-8">
</head>
<body>
<table cellspacing="0" cellpadding="0" width="100%" border="0">
    <tr>
        <td width="<?= $leftWidth; ?>%" class="side-bar-menu"><?= $sideLink; ?></td>
        <td width="<?= $midWidth; ?>%"></td>
        <td width="<?= $rightWidth; ?>%">
            <table width="100%" border="0" class="entry-form">
                <tr>
                    <td>Word Search Module</td>
                </tr>
                <tr>
                    <td><?= $callerClass->responseData; ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
