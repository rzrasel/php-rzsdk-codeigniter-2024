<?php
use RzSDK\Quiz\Model\HTTP\Request\Language\Parameter\RequestLanguageEntryQueryModel;
?>
<?php
$tempLanguageQueryModel = new RequestLanguageEntryQueryModel();
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
                <input type="text" name="<?= $tempLanguageQueryModel->language_name; ?>" value="<?= $languageEntryActivity->languageEntryQueryModel->language_name; ?>" placeholder="Language Name" required="required" />
            </td>
        </tr>
        <tr><td></td><td height="30px"></td></tr>
        <tr>
            <td></td>
            <td>
                <input type="hidden" name="<?= $tempLanguageQueryModel->getLanguageEntryFormName(); ?>" value="language_entry_form_value">
            </td>
        </tr>
        <tr>
            <td></td>
            <td class="form-button"><button class="button-6" type="submit">Submit</button></td>
        </tr>
    </table>
</form>
<?php
$tempLanguageQueryModel = null;
?>