<?php
use RzSDK\Universal\Search\Word\Helper\WordSearchActionParameter;
use RzSDK\Universal\Search\Word\From\WordSearchTableWithActionFrom;
?>
<?php
$wordEditUrl = "{$projectBaseUrl}/word-meaning-edit/word-meaning-edit.php";
$wordDeleteUrl = "{$projectBaseUrl}/word-meaning-search/word-meaning-search.php";
$actionParameter = new WordSearchActionParameter();
$actionParameter->editUrl = $wordEditUrl;
$actionParameter->deleteUrl = $wordDeleteUrl;
//
$wordSearchTableWithActionFrom = new WordSearchTableWithActionFrom();
$wordSearchTableWithActionFrom
    ->setSearchWord("")
    ->setActionParameter($actionParameter)
    ->execute();
?>
<?php
echo $wordSearchTableWithActionFrom->getHtmlTable();
?>
