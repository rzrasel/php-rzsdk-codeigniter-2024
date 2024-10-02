<?php
namespace RzSDK\Universal\Search\Word\From;
?>
<?php

use RzSDK\Universal\Search\Word\Helper\WordSearchActionParameter;
use RzSDK\Universal\Search\Word\Helper\WordSearchHelper;
use RzSDK\Database\Schema\TblWordMapping;
use RzSDK\Log\DebugLog;
?>
<?php
class WordSearchTableWithActionFrom extends WordSearchHelper {
    //
    private $searchWord = "";
    private WordSearchActionParameter $actionParameter;
    //

    public function setSearchWord($word) {
        self::setSearchingWord($word);
        return $this;
    }

    public function setActionParameter(WordSearchActionParameter $parameter) {
        $this->actionParameter = $parameter;
        return $this;
    }

    public function execute() {
        $dbResult = self::executeSearch();
        if(empty($dbResult)) {
            return null;
        }
        //
        $tempTblWordMapping = new TblWordMapping();
        $colWordId = $tempTblWordMapping->word_id;
        $colWord = $tempTblWordMapping->word;
        $colPronunciation = $tempTblWordMapping->pronunciation;
        $colMeaning = $tempTblWordMapping->meaning;
        $tempTblWordMapping = null;
        //
        $actionUrl = "action_url";
        if(isset($this->actionParameter)) {
            //echo "====================";
            if($this->actionParameter->isAnd) {
                $this->actionParameter->editUrl = "&";
                $this->actionParameter->deleteUrl = "&";
            } else {
                $this->actionParameter->editUrl = "?";
                $this->actionParameter->deleteUrl = "?";
            }
            $actionUrl = "";
        }
        //
        $htmlTable = "\n<table class=\"table-search-word\" width=\"100%\">\n";
        $htmlTable .= "<tr>\n";
        $htmlTable .= "<th>Word</th>\n";
        $htmlTable .= "<th>Pronunciation</th>\n";
        $htmlTable .= "<th>Meaning</th>\n";
        $htmlTable .= "<th>Edit</th>\n";
        $htmlTable .= "<th>Delete</th>\n";
        $htmlTable .= "</tr>\n";
        //
        $counter = 0;
        foreach($dbResult as $row) {
            //DebugLog::log($row);
            $wordId = $row[$colWordId];
            $word = $row[$colWord];
            $pronunciation = $row[$colPronunciation];
            $meaning = $row[$colMeaning];
            /*echo $word;
            echo "<br />";*/
            $editAction = "<a>Edit</a>";
            $deleteAction = "<a>Delete</a>";
            //echo "{$actionUrl}====================";
            if(empty($actionUrl)) {
                //echo "====================";
                $actionUrl = "{$this->actionParameter->wordId}={$wordId}"
                    . "&{$this->actionParameter->word}={$word}"
                    . "&{$this->actionParameter->pronunciation}={$pronunciation}"
                    . "&{$this->actionParameter->meaning}={$meaning}";
                //
                $editAction = $this->actionParameter->editUrl . $actionUrl;
                $deleteAction = $this->actionParameter->deleteUrl . $actionUrl;
                //
                $editAction = "<a href=\"{$editAction}\">Edit</a>";
                $deleteAction = "<a href=\"{$deleteAction}\">Delete</a>";
            }
            $htmlTable .= "<tr>\n";
            $htmlTable .= "<td>{$word}</td>\n";
            $htmlTable .= "<td>{$pronunciation}</td>\n";
            $htmlTable .= "<td>{$meaning}</td>\n";
            $htmlTable .= "<td>{$editAction}</td>\n";
            $htmlTable .= "<td>{$deleteAction}</td>\n";
            $htmlTable .= "</tr>\n";
            if($actionUrl != "action_url") {
                $actionUrl = "";
            }
            $counter++;
        }
        $htmlTable .= "</table>\n";
        self::closeDbResult();
        if($counter <= 0) {
            return null;
        }
        echo $htmlTable;
    }
}
?>