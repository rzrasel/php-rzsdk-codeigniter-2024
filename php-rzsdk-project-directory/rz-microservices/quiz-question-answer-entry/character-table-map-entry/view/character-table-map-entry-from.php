<?php
use RzSDK\Quiz\Model\HTTP\Request\Book\Name\Parameter\RequestBookNameEntryQueryModel;
use RzSDK\Universal\Book\Token\PullBookTokenList;
use RzSDK\Universal\Language\Select\Option\BuildLanguageSelectOptions;
use RzSDK\Universal\Book\Token\BuildBookTokenSelectOptions;
use RzSDK\Universal\Character\Token\BuildCharacterTokenSelectOptions;
use RzSDK\Quiz\Model\HTTP\Request\Character\Map\Parameter\RequestCharacterMapEntryQueryModel;
use RzSDK\Utils\String\StringHelper;
use RzSDK\Log\DebugLog;
?>
<?php
$tempCharacterMapEntryQueryModel = new RequestCharacterMapEntryQueryModel();
?>
<?php
//echo $bookNameEntryActivity->bookNameEntryQueryModel->language_id;
/*$languageSelectOptions = new BuildLanguageSelectOptions();
$languageSelectOptions
    ->setFieldName($tempBookNameEntryQueryModel->language_id)
    ->setSelectedOption($bookNameEntryActivity->bookNameEntryQueryModel->language_id);
//
$bookTokenSelectOptions = new BuildBookTokenSelectOptions();
$bookTokenSelectOptions
    ->setFieldName($tempBookNameEntryQueryModel->book_token_id)
    ->setSelectedOption($bookNameEntryActivity->bookNameEntryQueryModel->book_token_id);*/
//
$characterTokenSelectOptions = new BuildCharacterTokenSelectOptions();
$characterTokenSelectOptions
    ->setFieldName($tempCharacterMapEntryQueryModel->character_token_id)
    ->setSelectedOption($characterTableMapEntryActivity->characterMapEntryQueryModel->character_name);
?>
<?php
/*$bookNameIsDefault = $bookNameEntryActivity->bookNameEntryQueryModel->book_name_is_default;
$isChecked = "";
if(is_numeric($bookNameIsDefault)) {
    if($bookNameIsDefault == 1) {
        $isChecked = "checked=\"true\"";
    }
}*/
?>
<?php
$str = "আমি";
foreach(mb_str_split($str) as $char) {
    echo $char;
    echo "<br />";
}
?>
<form action="<?= $pageUrlOnly; ?>" method="POST" enctype="multipart/form-data">
    <table border="1" class="table-entry-form-field-container">
        <tr>
            <td width="100px" height="10px" style="border: #5cb730 1px;"></td>
            <td width="200px" style="border: #5cb730 1px;"></td>
        </tr>
        <tr>
            <td class="table-entry-form-field-left">Character Token: </td>
            <td class="table-entry-form-field-right">
                <?= $characterTokenSelectOptions->execute(); ?>
            </td>
        </tr>
        <tr>
            <td height="20px"></td>
            <td></td>
        </tr>
        <tr>
            <td class="table-entry-form-field-left">Character: </td>
            <td class="table-entry-form-field-right">
                <input type="text" name="<?= $tempCharacterMapEntryQueryModel->character_name; ?>" value="<?= $characterTableMapEntryActivity->characterMapEntryQueryModel->character_name; ?>" placeholder="Unicode Character" required="required" />
            </td>
        </tr>
        <tr>
            <td height="20px"></td>
            <td></td>
        </tr>
        <tr><td></td><td height="30px"></td></tr>
        <tr>
            <td></td>
            <td>
                <input type="hidden" name="<?= $tempCharacterMapEntryQueryModel->getEntryFormName(); ?>" value="character_map_entry_form_value">
            </td>
        </tr>
        <tr>
            <td></td>
            <td class="form-button"><button class="button-6" type="submit">Submit</button></td>
        </tr>
    </table>
</form>