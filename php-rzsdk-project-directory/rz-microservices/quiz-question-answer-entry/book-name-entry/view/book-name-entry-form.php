<?php
use RzSDK\Quiz\Model\HTTP\Request\Book\Name\Parameter\RequestBookNameEntryQueryModel;
use RzSDK\Universal\Book\Token\PullBookTokenList;
use RzSDK\Universal\Language\Select\Option\BuildLanguageSelectOptions;
use RzSDK\Universal\Book\Token\BuildBookTokenSelectOptions;
use RzSDK\Log\DebugLog;
?>
<?php
$tempBookNameEntryQueryModel = new RequestBookNameEntryQueryModel();
?>
<?php
$languageSelectOptions = new BuildLanguageSelectOptions();
$languageSelectOptions
    ->setFieldName($tempBookNameEntryQueryModel->language_id)
    ->setSelectedOption($bookNameEntryActivity->bookNameEntryQueryModel->language_id);
/*$languageSelectOptions->setFieldName("lan")
    ->setIsRequired(false)
    ->setSelectedOption("172527478414712921");*/
$bookTokenSelectOptions = new BuildBookTokenSelectOptions();
$bookTokenSelectOptions
    ->setFieldName($tempBookNameEntryQueryModel->book_token_id)
    ->setSelectedOption($bookNameEntryActivity->bookNameEntryQueryModel->book_token_id);
?>
<?php
$bookNameIsDefault = $bookNameEntryActivity->bookNameEntryQueryModel->book_name_is_default;
$isChecked = "";
if(is_numeric($bookNameIsDefault)) {
    if($bookNameIsDefault == 1) {
        $isChecked = "checked=\"true\"";
    }
}
?>
<form action="<?= $pageUrlOnly; ?>" method="POST" enctype="multipart/form-data">
    <table border="1" class="table-entry-form-field-container">
        <tr>
            <td width="100px" height="10px" style="border: #5cb730 1px;"></td>
            <td width="200px" style="border: #5cb730 1px;"></td>
        </tr>
        <tr>
            <td class="table-entry-form-field-left">Language: </td>
            <td class="table-entry-form-field-right">
                <?= $languageSelectOptions->execute(); ?>
            </td>
        </tr>
        <tr>
            <td height="20px"></td>
            <td></td>
        </tr>
        <tr>
            <td class="table-entry-form-field-left">Book Token: </td>
            <td class="table-entry-form-field-right">
                <?= $bookTokenSelectOptions->execute(); ?>
            </td>
        </tr>
        <tr>
            <td height="20px"></td>
            <td></td>
        </tr>
        <tr>
            <td class="table-entry-form-field-left">Book Name: </td>
            <td class="table-entry-form-field-right">
                <input type="text" name="<?= $tempBookNameEntryQueryModel->book_name; ?>" value="<?= $bookNameEntryActivity->bookNameEntryQueryModel->book_name; ?>" placeholder="Book Name" required="required" />
            </td>
        </tr>
        <tr>
            <td height="20px"></td>
            <td></td>
        </tr>
        <tr>
            <td>Is Default: </td>
            <td style="text-align: right; align-content: end;">
                <input type="hidden" name="<?= $tempBookNameEntryQueryModel->book_name_is_default; ?>" value="0" />
                <input type="checkbox" name="<?= $tempBookNameEntryQueryModel->book_name_is_default; ?>" value="1" <?= $isChecked; ?> />
            </td>
        </tr>
        <tr><td></td><td height="30px"></td></tr>
        <tr>
            <td></td>
            <td>
                <input type="hidden" name="<?= $tempBookNameEntryQueryModel->getEntryFormName(); ?>" value="book_name_entry_form_value">
            </td>
        </tr>
        <tr>
            <td></td>
            <td class="form-button"><button class="button-6" type="submit">Submit</button></td>
        </tr>
    </table>
</form>