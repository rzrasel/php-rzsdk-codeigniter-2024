<?php
use RzSDK\Model\HTTP\Request\Parameter\Word\Meaning\NewLine\RequestWordMeaningNewLineQueryModel;
?>
<?php
$tempWordMeaningNewLineQueryModel = new RequestWordMeaningNewLineQueryModel();
?>
<form action="<?= $pageUrlOnly; ?>" method="POST" enctype="multipart/form-data">
    <table border="1" class="table-entry-form-field-container">
        <tr>
            <td width="100px" height="10px" style="border: #5cb730 1px;"></td>
            <td width="200px" style="border: #5cb730 1px;"></td>
        </tr>
        <tr>
            <td class="table-entry-form-field-left">Source Text: </td>
            <td class="table-entry-form-field-right">
                <textarea name="<?= $tempWordMeaningNewLineQueryModel->source_text; ?>" placeholder="Source Text."><?= $wordMeaningNewLineActivity->wordMeaningNewLineQueryModel->source_text; ?></textarea>
            </td>
        </tr>
        <tr>
            <td height="20px"></td>
            <td></td>
        </tr>
        <tr>
            <td class="table-entry-form-field-left">Formatted Text: </td>
            <td class="table-entry-form-field-right">
                <textarea name="<?= $tempWordMeaningNewLineQueryModel->formatted_text; ?>"  placeholder="Formatted Text." readonly="readonly"><?= $wordMeaningNewLineActivity->wordMeaningNewLineQueryModel->formatted_text; ?></textarea>
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
                <input type="hidden" name="<?= $tempWordMeaningNewLineQueryModel->getEntryFormName(); ?>" value="word_meaning_new_line_form_value">
            </td>
        </tr>
        <tr>
            <td></td>
            <td class="form-button"><button class="button-6" type="submit">Submit</button></td>
        </tr>
    </table>
</form>