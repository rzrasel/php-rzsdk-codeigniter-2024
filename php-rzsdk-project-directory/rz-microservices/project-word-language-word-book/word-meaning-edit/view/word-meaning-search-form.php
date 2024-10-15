<?php
use RzSDK\Universal\Search\Word\Helper\WordSearchActionParameter;
use RzSDK\Universal\Search\Word\From\WordSearchTableWithActionFrom;
use RzSDK\Model\HTTP\Request\Parameter\Word\Meaning\Search\Edit\RequestWordMeaningSearchQueryModel;
?>
<?php
$tempWordMeaningSearchQueryModel = new RequestWordMeaningSearchQueryModel();
?>
<?php
//
//$searchWord = $wordMeaningSearchActivity->wordMeaningSearchQueryModel->search_word;
$searchWord = "";
$searchWordParam = $tempWordMeaningSearchQueryModel->search_word;
if(!empty($_REQUEST)) {
    if(!empty($_REQUEST[$searchWordParam])) {
        $searchWord = $_REQUEST[$searchWordParam];
    }
}
$searchWordParamValue = "";
$isAnd = false;
if(!empty($searchWord)) {
    $searchWordParamValue = "?{$searchWordParam}={$searchWord}";
    $isAnd = true;
}
//
$wordEditUrl = "{$projectBaseUrl}/word-meaning-edit/word-meaning-edit.php{$searchWordParamValue}";
$wordDeleteUrl = "{$projectBaseUrl}/word-meaning-search/word-meaning-search.php";
$actionParameter = new WordSearchActionParameter();
$actionParameter->editUrl = $wordEditUrl;
$actionParameter->deleteUrl = $wordDeleteUrl;
$actionParameter->wordId = $tempWordMeaningSearchQueryModel->word_id;
$actionParameter->word = $tempWordMeaningSearchQueryModel->word;
$actionParameter->pronunciation = $tempWordMeaningSearchQueryModel->pronunciation;
$actionParameter->meaning = $tempWordMeaningSearchQueryModel->meaning;
//
$wordSearchTableWithActionFrom = new WordSearchTableWithActionFrom();
$wordSearchTableWithActionFrom
    ->setSearchWord($searchWord)
    ->setActionParameter($actionParameter)
    ->setIsAnd($isAnd)
    ->execute();
?>
<table border="1" width="100%">
    <tr>
        <td>
            <form action="<?= $pageUrlOnly; ?>" method="POST" enctype="multipart/form-data">
                <table border="1" class="table-entry-form-field-container">
                    <tr>
                        <td width="100px" height="10px" style="border: #5cb730 1px;"></td>
                        <td width="400px" style="border: #5cb730 1px;"></td>
                        <td width="200px" style="border: #5cb730 1px;"></td>
                    </tr>
                    <tr>
                        <td class="table-entry-form-field-left">Word: </td>
                        <td class="table-entry-form-field-right">
                            <input type="text" name="<?= $tempWordMeaningSearchQueryModel->search_word; ?>" value="<?= $searchWord; ?>" placeholder="Search Word" required="required" />
                        </td>
                        <td class="form-button" style="padding-left: 20px;"><button class="button-6" type="submit">Search</button></td>
                    </tr>
                    <tr>
                        <td height="20px"></td>
                        <td><input type="hidden" name="<?= $tempWordMeaningSearchQueryModel->getEntryFormName(); ?>" value="word_meaning_search_form_value"></td>
                        <td></td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
    <tr>
        <td>
<?php
echo $wordSearchTableWithActionFrom->getHtmlTable();
?>
        </td>
    </tr>
</table>
