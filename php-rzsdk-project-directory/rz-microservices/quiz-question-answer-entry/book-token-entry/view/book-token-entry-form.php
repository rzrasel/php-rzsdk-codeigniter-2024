<?php
use RzSDK\Quiz\Model\HTTP\Request\Book\Token\Parameter\RequestBookTokenEntryQueryModel;
?>
<?php
$tempBookTokenEntryQueryModel = new RequestBookTokenEntryQueryModel();
?>
<form action="<?= $pageUrlOnly; ?>" method="POST" enctype="multipart/form-data">
    <table border="1" class="table-entry-form-field-container">
        <tr>
            <td width="100px" height="10px" style="border: #5cb730 1px;"></td>
            <td width="200px" style="border: #5cb730 1px;"></td>
        </tr>
        <tr>
            <td class="table-entry-form-field-left">Book Token Name: </td>
            <td class="table-entry-form-field-right">
                <input type="text" name="<?= $tempBookTokenEntryQueryModel->book_token_name; ?>" value="<?= $bookTokenEntryActivity->bookTokenEntryQueryModel->book_token_name; ?>" placeholder="Book Token Name (English Only)" required="required" />
            </td>
        </tr>
        <tr><td></td><td height="30px"></td></tr>
        <tr>
            <td></td>
            <td>
                <input type="hidden" name="<?= $tempBookTokenEntryQueryModel->getEntryFormName(); ?>" value="language_entry_form_value">
            </td>
        </tr>
        <tr>
            <td></td>
            <td class="form-button"><button class="button-6" type="submit">Submit</button></td>
        </tr>
    </table>
</form>