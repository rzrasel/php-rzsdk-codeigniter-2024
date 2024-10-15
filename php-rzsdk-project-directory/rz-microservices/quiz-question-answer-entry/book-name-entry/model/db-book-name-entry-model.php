<?php
namespace RzSDK\Quiz\Model\Database\Book\Name\Entry;
?>
<?php
use RzSDK\Database\Quiz\TblBookName;
use RzSDK\Quiz\Model\HTTP\Request\Book\Name\Parameter\RequestBookNameEntryQueryModel;
use RzSDK\Identification\UniqueIntId;
use RzSDK\Utils\Id\Generator\IdGenerator;
use RzSDK\Utils\String\StringHelper;
use RzSDK\DateTime\DateTime;
?>
<?php
class DbBookNameEntryModel {
    public function getBookNameInsertDataSet(RequestBookNameEntryQueryModel $bookNameEntryQueryModel, bool $isDefault = false): TblBookName {
        //
        $uniqueIntId = new UniqueIntId();
        $bookNameId = $uniqueIntId->getId();
        $sysUserId = IdGenerator::getSysUserId();
        $currentDate = DateTime::getCurrentDateTime();
        //
        $bookName = StringHelper::toSingleSpace($bookNameEntryQueryModel->book_name);
        $bookName = StringHelper::toUCWords($bookName);
        $bookSlug = StringHelper::toSingleSpace($bookNameEntryQueryModel->book_name_slug);
        $bookSlug = StringHelper::toSlugify($bookSlug);
        //
        $tblBookName = new TblBookName();
        $tblBookName->lan_id = $bookNameEntryQueryModel->language_id;
        $tblBookName->book_token_id = $bookNameEntryQueryModel->book_token_id;
        $tblBookName->book_name_id = $bookNameId;
        $tblBookName->book_name = $bookName;
        $tblBookName->slug = $bookSlug;
        $tblBookName->status = true;
        $tblBookName->is_default = $isDefault;
        $tblBookName->modified_by = $sysUserId;
        $tblBookName->created_by = $sysUserId;
        $tblBookName->modified_date = $currentDate;
        $tblBookName->created_date = $currentDate;
        //
        return $tblBookName;
    }
}
?>
