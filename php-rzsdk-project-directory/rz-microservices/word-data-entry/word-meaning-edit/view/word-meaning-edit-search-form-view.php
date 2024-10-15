<?php
?>
<?php
$searchWord = "";
if(!empty($_POST)) {
    $searchWord = $_POST["search_word"];
}
?>
<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="POST">
    <table class="table-entry-form-field-container">
        <tr><td height="20px"></td><td></td><td></td></tr>
        <tr><td width="150px"></td><td width="250px" style="padding: 0px 15px;"></td><td width="250px"></td></tr>
        <!--<tr>
            <td></td><td><input type="button" value="Submit" /></td>
        </tr>-->
        <tr>
            <td>Word Language: </td>
            <td style="padding: 0px 15px;">
                <select name="word_language" required="required">
                    <!--<option value="172157799761295096">Bangla Language</option>
                    <option value="172157831436333409" selected="select">English Language</option>-->
                    <?= $wordLanguageOptions; ?>
                </select>
            </td>
            <td>
                <input type="text" name="search_word" value="<?= $searchWord; ?>" required="required" placeholder="Search Word">
            </td>
        </tr>
        <tr><td height="20px"></td><td></td><td></td></tr>
        <tr>
            <td></td>
            <td></td>
            <td class="form-button"><button type="submit" class="button-6">Search</button></td>
        </tr>
    </table>
</form>