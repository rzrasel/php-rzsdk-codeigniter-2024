<?php
use RzSDK\URL\SiteUrl;
use RzSDK\Log\DebugLog;
?>
<form action="<?= SiteUrl::getFullUrl(); ?>" method="POST">
    <table class="table-entry-form-field-container">
        <tr>
            <td class="table-entry-form-field-left">Word: </td>
            <td class="table-entry-form-field-right">
                <input type="text" name="word" value="<?= $word; ?>" placeholder="Search Word" required="required" readonly="readonly" />
            </td>
        </tr>
        <tr><td height="20px"></td><td></td></tr>
        <tr>
            <td>Meaning Language: </td>
            <td>
                <select name="meaning_language" required="required">
                    <!--<option value="172157799761295096" selected="select">Bangla Language</option>
                    <option value="172157831436333409">English Language</option>-->
                    <?= $meaningLanguageOptions; ?>
                </select>
            </td>
        </tr>
        <tr><td height="20px"></td><td></td></tr>
        <tr>
            <td>Word Meaning: </td>
            <td>
                <input type="text" name="meaning" value="<?= $meaning; ?>" placeholder="Word Meaning" required="required" />
            </td>
        </tr>
        <tr><td height="20px"></td><td></td></tr>
        <tr>
            <td></td>
            <td>
                <input type="hidden" name="<?= $wordMeaningEditModule->wordMeaningEditQueryModel->getWordMeaningEditFormField(); ?>" value="word_edit_entry_form_value">
                <input type="hidden" name="word_language" value="<?= $wordLanguage; ?>">
                <input type="hidden" name="url_word_id" value="<?= $urlWordId; ?>">
                <input type="hidden" name="search_word" value="<?= $searchWord; ?>">
                <input type="hidden" name="url_word" value="<?= $urlWord; ?>">
                <input type="hidden" name="meaning_id" value="<?= $meaningId; ?>">
            </td>
        </tr>
        <tr><td height="20px"></td><td></td></tr>
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