<?php
use RzSDK\Quiz\Model\HTTP\Request\Book\Information\Parameter\RequestBookInfoEntryQueryModel;
?>
<?php
$tempBookInfoEntryQueryModel = new RequestBookInfoEntryQueryModel();
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
                <input type="text" name="<?= $tempBookInfoEntryQueryModel->category_token_name; ?>" value="<?= $bookInfoEntryActivity->bookInfoEntryQueryModel->category_token_name; ?>" placeholder="Category Token Name" required="required" />
            </td>
        </tr>
        <tr><td height="20px"></td><td></td></tr>
        <tr>
            <td class="table-entry-form-field-left">Book Name: </td>
            <td class="table-entry-form-field-right">
                <input type="text" name="<?= $tempBookInfoEntryQueryModel->category_token_name; ?>" value="<?= $bookInfoEntryActivity->bookInfoEntryQueryModel->category_token_name; ?>" placeholder="Category Token Name" required="required" />
            </td>
        </tr>
        <tr><td height="20px"></td><td></td></tr>
        <tr>
            <td class="table-entry-form-field-left">Book Name: </td>
            <td class="table-entry-form-field-right">
                <input type="text" name="<?= $tempBookInfoEntryQueryModel->category_token_name; ?>" value="<?= $bookInfoEntryActivity->bookInfoEntryQueryModel->category_token_name; ?>" placeholder="Category Token Name" required="required" />
            </td>
        </tr>
        <tr><td height="20px"></td><td></td></tr>
        <tr>
            <td class="table-entry-form-field-left">Category Token Name: </td>
            <td class="table-entry-form-field-right">
                <input type="text" name="<?= $tempBookInfoEntryQueryModel->category_token_name; ?>" value="<?= $bookInfoEntryActivity->bookInfoEntryQueryModel->category_token_name; ?>" placeholder="Category Token Name" required="required" />
            </td>
        </tr>
        <tr><td></td><td height="30px"></td></tr>
        <tr>
            <td></td>
            <td>
                <input type="hidden" name="<?= $tempBookInfoEntryQueryModel->getEntryFormName(); ?>" value="book_info_entry_form_value">
            </td>
        </tr>
        <tr>
            <td></td>
            <td class="form-button"><button class="button-6" type="submit">Submit</button></td>
        </tr>
    </table>
</form>