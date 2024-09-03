<?php
namespace RzSDK\Quiz\Model\Database\Book\Token\Entry;
?>
<?php
use RzSDK\Identification\UniqueIntId;
use RzSDK\DateTime\DateTime;
use RzSDK\Utils\Id\Generator\IdGenerator;
use RzSDK\Database\Quiz\TblBookIndex;
use RzSDK\Quiz\Model\HTTP\Request\Book\Token\Parameter\RequestBookTokenEntryQueryModel;
use RzSDK\Utils\String\StringHelper;
use RzSDK\Log\DebugLog;
?>
<?php
class DbBookTokenEntryModel {
    public function getBookTokenInsertDataSet(RequestBookTokenEntryQueryModel $bookTokenEntryQueryModel): TblBookIndex {
        //
        $uniqueIntId = new UniqueIntId();
        $bookTokenId = $uniqueIntId->getId();
        $sysUserId = IdGenerator::getSysUserId();
        $currentDate = DateTime::getCurrentDateTime();
        //
        $bookTokenName = trim($bookTokenEntryQueryModel->book_token_name);
        $bookTokenName = StringHelper::toSingleSpace($bookTokenName);
        $bookTokenName = StringHelper::toUCWords($bookTokenName);
        $bookTokenSlug = strtolower($bookTokenName);
        $bookTokenSlug = str_replace(" ","-", $bookTokenSlug);
        //
        $tblBookIndex = new TblBookIndex();
        //
        $tblBookIndex->book_token_id = $bookTokenId;
        $tblBookIndex->book_token_name = $bookTokenName;
        $tblBookIndex->slug = $bookTokenSlug;
        $tblBookIndex->status = true;
        $tblBookIndex->modified_by = $sysUserId;
        $tblBookIndex->created_by = $sysUserId;
        $tblBookIndex->modified_date = $currentDate;
        $tblBookIndex->created_date = $currentDate;
        //
        return $tblBookIndex;
    }
}
?>
