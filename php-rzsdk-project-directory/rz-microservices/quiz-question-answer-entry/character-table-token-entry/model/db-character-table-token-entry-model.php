<?php
namespace RzSDK\Quiz\Model\Database\Character\Index\Entry;
?>
<?php
use RzSDK\Database\Quiz\TblCharacterTableIndex;
use RzSDK\Identification\UniqueIntId;
use RzSDK\Utils\Id\Generator\IdGenerator;
use RzSDK\Utils\String\StringHelper;
use RzSDK\DateTime\DateTime;
?>
<?php
class DbCharacterTableTokenEntryModel {
    public function getCharacterTableTokenInsertDataSet($char): TblCharacterTableIndex {
        //
        $uniqueIntId = new UniqueIntId();
        $charIndexId = $uniqueIntId->getId();
        $sysUserId = IdGenerator::getSysUserId();
        $currentDate = DateTime::getCurrentDateTime();
        //
        if(is_int($char)) {
            $char = intval($char);
        }
        $charAscii = StringHelper::toAscii($char);
        $hexCode = StringHelper::toHex($char);
        $uPlusCode = StringHelper::toHexPlus($char);
        $uCode = StringHelper::toUHex($char);
        //
        $tblCharacterTableIndex = new TblCharacterTableIndex();
        $tblCharacterTableIndex->char_index_id = $charIndexId;
        $tblCharacterTableIndex->str_char = $char;
        $tblCharacterTableIndex->char_ascii = $charAscii;
        $tblCharacterTableIndex->hex_code = $hexCode;
        $tblCharacterTableIndex->u_plus_code = $uPlusCode;
        $tblCharacterTableIndex->u_code = $uCode;
        $tblCharacterTableIndex->status = true;
        $tblCharacterTableIndex->modified_by = $sysUserId;
        $tblCharacterTableIndex->created_by = $sysUserId;
        $tblCharacterTableIndex->modified_date = $currentDate;
        $tblCharacterTableIndex->created_date = $currentDate;
        //
        return $tblCharacterTableIndex;
    }
}
?>
