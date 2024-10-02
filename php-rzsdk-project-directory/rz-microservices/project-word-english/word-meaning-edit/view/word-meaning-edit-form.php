<?php
use RzSDK\Model\HTTP\Request\Parameter\Word\Meaning\Edit\RequestWordMeaningEditQueryModel;
?>
<?php
$tempWordMeaningEditQueryModel = new RequestWordMeaningEditQueryModel();
?>
<form action="<?= $pageUrlOnly; ?>" method="POST" enctype="multipart/form-data">
    <table border="1" class="table-entry-form-field-container">
        <tr>
            <td width="100px" height="10px" style="border: #5cb730 1px;"></td>
            <td width="200px" style="border: #5cb730 1px;"></td>
        </tr>
        <tr>
            <td class="table-entry-form-field-left">Word Id: </td>
            <td class="table-entry-form-field-right">
                <input type="text" name="<?= $tempWordMeaningEditQueryModel->word_id; ?>" value="<?= $wordMeaningEditActivity->wordMeaningEditQueryModel->word_id; ?>" placeholder="Word Id" required="required" readonly="readonly" />
            </td>
        </tr>
        <tr>
            <td height="20px"></td>
            <td></td>
        </tr>
        <tr>
            <td class="table-entry-form-field-left">Word: </td>
            <td class="table-entry-form-field-right">
                <input type="text" name="<?= $tempWordMeaningEditQueryModel->word; ?>" value="<?= $wordMeaningEditActivity->wordMeaningEditQueryModel->word; ?>" placeholder="Word" required="required" />
            </td>
        </tr>
        <tr>
            <td height="20px"></td>
            <td></td>
        </tr>
        <tr>
            <td class="table-entry-form-field-left">Pronunciation: </td>
            <td class="table-entry-form-field-right">
                <input type="text" name="<?= $tempWordMeaningEditQueryModel->pronunciation; ?>" value="<?= $wordMeaningEditActivity->wordMeaningEditQueryModel->pronunciation; ?>" placeholder="Pronunciation" required="required" />
            </td>
        </tr>
        <tr>
            <td height="20px"></td>
            <td></td>
        </tr>
        <tr>
            <td class="table-entry-form-field-left">Meaning: </td>
            <td class="table-entry-form-field-right">
                <input type="text" name="<?= $tempWordMeaningEditQueryModel->meaning; ?>" value="<?= $wordMeaningEditActivity->wordMeaningEditQueryModel->meaning; ?>" placeholder="Meaning" required="required" />
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
                <input type="hidden" name="<?= $tempWordMeaningEditQueryModel->getEntryFormName(); ?>" value="word_meaning_edit_form_value">
            </td>
        </tr>
        <tr>
            <td></td>
            <td class="form-button"><button class="button-6" type="submit">Submit</button></td>
        </tr>
    </table>
</form>