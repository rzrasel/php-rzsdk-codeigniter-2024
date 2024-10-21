<?php
require_once("include.php");
?>
<?php
use RzSDK\Padding\Utils\PaddingPlace;
use RzSDK\String\Utils\TextCase;
use RzSDK\Code\Module\NumOfPaddingSelectBox;
use RzSDK\Code\Module\NumOfTabSelectBox;
use RzSDK\Code\Module\PaddingPlaceSelectBox;
use RzSDK\Code\Module\TextCaseSelectBox;
use RzSDK\String\Utils\StringUtils;
use RzSDK\Activity\Formatter\CommentFormatterActivity;
?>
<?php
$commentText = "";
$formattedText = "";
$selectedPaddingPlace = PaddingPlace::CENTER->value;
$selectedTextCase = TextCase::UPPER->value;
$selectedPaddingNum = 64;
$selectedNumOfTabs = 0;
//
if(!empty($_POST)) {
    $selectedPaddingNum = $_POST["total_characters"];
    $selectedNumOfTabs = $_POST["total_tabs"];
    $selectedPaddingPlace = $_POST["padding_place"];
    $selectedTextCase = $_POST["text_case"];
    $commentText = $_POST["comment_text"];
    $commentFormatterActivity = new CommentFormatterActivity();
    $commentFormatterActivity->execute($_POST);
    //$formattedText = StringUtils::toCaseConversion($commentText, TextCase::UCWORDS);
    $formattedText = $commentFormatterActivity->getFormattedText();
}
//
$numOfPaddingSelectBox = NumOfPaddingSelectBox::getSelectBox($selectedPaddingNum);
$numOfTabSelectBox = NumOfTabSelectBox::getSelectBox($selectedNumOfTabs);
$paddingPlaceSelectBox = PaddingPlaceSelectBox::getSelectBox($selectedPaddingPlace);
$textCaseSelectBox = TextCaseSelectBox::getSelectBox($selectedTextCase);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--<meta http-equiv="X-UA-Compatible" content="IE=edge">-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comment Formatter</title>
    <link href="css/style.css"  rel="stylesheet" type="text/css" charset="utf-8">
    <style>
    </style>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td style="vertical-align: middle; padding: 20px 100px;" align="center" valign="center">
            <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
                <table align="left" border="0" width="100%">
                    <tr>
                        <td height="50px"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Total Characters</td>
                        <td><?= $numOfPaddingSelectBox; ?></td>
                    </tr>
                    <tr>
                        <td height="20px"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Number Of Tabs</td>
                        <td><?= $numOfTabSelectBox; ?></td>
                    </tr>
                    <tr>
                        <td height="20px"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Padding Place</td>
                        <td><?= $paddingPlaceSelectBox; ?></td>
                    </tr>
                    <tr>
                        <td height="20px"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Text Case</td>
                        <td><?= $textCaseSelectBox; ?></td>
                    </tr>
                    <tr>
                        <td height="20px"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Comment Text</td>
                        <td>
                            <input type="text" name="comment_text" value="<?= $commentText; ?>" required="required" />
                        </td>
                    </tr>
                    <tr>
                        <td height="20px"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Formatted Comment Text</td>
                        <td>
                            <input type="text" name="formatted_text" value="<?= $formattedText; ?>" onclick="this.select();" />
                        </td>
                    </tr>
                    <tr>
                        <td height="30px"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><button type="submit">Submit</button></td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
</table>
</body>
</html>