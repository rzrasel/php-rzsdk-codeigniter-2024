<?php
use RzSDK\Model\HTTP\Request\Parameter\Word\Meaning\Side\RequestWordMeaningSideBySideQueryModel;
use RzSDK\Model\HTTP\Request\Parameter\Word\Meaning\Pronunciation\RequestWordMeaningPronunciationQueryModel;
?>
<?php
$tempWordMeaningPronunciationQueryModel = new RequestWordMeaningPronunciationQueryModel();
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
                <textarea name="<?= $tempWordMeaningPronunciationQueryModel->source_text; ?>" placeholder="Source Text."><?= $wordMeaningPronunciationActivity->wordMeaningPronunciationQueryModel->source_text; ?></textarea>
            </td>
        </tr>
        <tr>
            <td height="20px"></td>
            <td></td>
        </tr>
        <tr>
            <td class="table-entry-form-field-left">Formatted Text: </td>
            <td class="table-entry-form-field-right">
                <textarea name="<?= $tempWordMeaningPronunciationQueryModel->formatted_text; ?>"  placeholder="Formatted Text." readonly="readonly"><?= $wordMeaningPronunciationActivity->wordMeaningPronunciationQueryModel->formatted_text; ?></textarea>
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
                <input type="hidden" name="<?= $tempWordMeaningPronunciationQueryModel->getEntryFormName(); ?>" value="word_meaning_side_by_side_form_value">
            </td>
        </tr>
        <tr>
            <td></td>
            <td class="form-button"><button class="button-6" type="submit">Submit</button></td>
        </tr>
    </table>
</form>