<?php
use RzSDK\Universal\Search\Word\From\WordSearchTableFrom;
use RzSDK\Universal\Search\Word\Helper\WordSearchActionParameter;
use RzSDK\Universal\Search\Word\From\WordSearchTableWithActionFrom;
?>
<?php
$actionParameter = new WordSearchActionParameter();
$actionParameter->editUrl = $projectBaseUrl;
$actionParameter->deleteUrl = $projectBaseUrl;
//
$wordSearchTableWithActionFrom = new WordSearchTableWithActionFrom();
$wordSearchTableWithActionFrom
    ->setSearchWord("")
    ->setActionParameter($actionParameter)
    ->execute();
?>