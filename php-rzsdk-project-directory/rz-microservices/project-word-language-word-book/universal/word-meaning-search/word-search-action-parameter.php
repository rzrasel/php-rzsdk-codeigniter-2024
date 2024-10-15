<?php
namespace RzSDK\Universal\Search\Word\Helper;
?>
<?php
use RzSDK\Log\DebugLog;
?>
<?php
class WordSearchActionParameter {
    public $editUrl;
    public $deleteUrl;
    public $wordId = "word-id";
    public $word = "word";
    public $pronunciation = "pronunciation";
    public $meaning = "meaning";
    public bool $isAnd = false;
}
?>