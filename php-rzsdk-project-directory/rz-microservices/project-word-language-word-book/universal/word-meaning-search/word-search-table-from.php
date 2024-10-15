<?php
namespace RzSDK\Universal\Search\Word\From;
?>
<?php
use RzSDK\Universal\Search\Word\Helper\WordSearchHelper;
use RzSDK\Database\Schema\TblWordMapping;
use RzSDK\Log\DebugLog;
?>
<?php
class WordSearchTableFrom extends WordSearchHelper {
    //
    private $searchWord = "";
    //

    public function setSearchWord($word) {
        self::setSearchingWord($word);
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
        $htmlTable = "<table class=\"table-search-word\" width=\"100%\">";
        $htmlTable .= "<tr>";
        $htmlTable .= "<th>Word</th>";
        $htmlTable .= "<th>Pronunciation</th>";
        $htmlTable .= "<th>Meaning</th>";
        $htmlTable .= "</tr>";
        //
        $counter = 0;
        foreach($dbResult as $row) {
            //DebugLog::log($row);
            $word = $row[$colWord];
            $pronunciation = $row[$colPronunciation];
            $meaning = $row[$colMeaning];
            /*echo $word;
            echo "<br />";*/
            $htmlTable .= "<tr>";
            $htmlTable .= "<td>{$word}</td>";
            $htmlTable .= "<td>{$pronunciation}</td>";
            $htmlTable .= "<td>{$meaning}</td>";
            $htmlTable .= "</tr>";
            $counter++;
        }
        $htmlTable .= "</table>";
        self::closeDbResult();
        if($counter <= 0) {
            return null;
        }
        echo $htmlTable;
    }
}
?>