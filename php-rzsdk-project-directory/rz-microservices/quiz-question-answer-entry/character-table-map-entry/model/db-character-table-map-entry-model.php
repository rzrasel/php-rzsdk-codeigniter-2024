<?php
namespace RzSDK\Quiz\Model\Database\Character\Map\Entry;
?>
<?php
use RzSDK\Database\Quiz\TblCharacterTableIndex;
use RzSDK\Database\Quiz\TblCharacterMapping;
use RzSDK\Identification\UniqueIntId;
use RzSDK\Log\DebugLog;
use RzSDK\Utils\Id\Generator\IdGenerator;
use RzSDK\Utils\String\StringHelper;
use RzSDK\DateTime\DateTime;
?>
<?php
class DbCharacterTableMapEntryModel {
    public function getCharacterMapInsertDataSet($tokenId, $tokenChar, $asciiChar): TblCharacterMapping {
        //
        $uniqueIntId = new UniqueIntId();
        $charMappingId = $uniqueIntId->getId();
        $sysUserId = IdGenerator::getSysUserId();
        $currentDate = DateTime::getCurrentDateTime();
        //
        if(is_int($tokenChar)) {
            $tokenChar = intval($tokenChar);
        }
        if(is_int($asciiChar)) {
            $asciiChar = intval($asciiChar);
        }
        //
        //$tokenChar = StringHelper::toAscii($tokenChar);
        //$char = $asciiChar;
        $charAscii = StringHelper::toAscii($asciiChar);
        $hexCode = StringHelper::toHex($asciiChar);
        //DebugLog::log($hexCode);
        $uPlusCode = StringHelper::toHexPlus($asciiChar);
        $uCode = StringHelper::toUHex($asciiChar);
        //
        $tblCharacterMapping = new TblCharacterMapping();
        $tblCharacterMapping->char_index_id = $tokenId;
        $tblCharacterMapping->char_mapping_id = $charMappingId;
        $tblCharacterMapping->str_char = $tokenChar;
        $tblCharacterMapping->ascii_char = $asciiChar;
        $tblCharacterMapping->char_ascii = $charAscii;
        $tblCharacterMapping->hex_code = "{$hexCode}";
        $tblCharacterMapping->u_plus_code = $uPlusCode;
        $tblCharacterMapping->u_code = $uCode;
        $tblCharacterMapping->status = true;
        $tblCharacterMapping->modified_by = $sysUserId;
        $tblCharacterMapping->created_by = $sysUserId;
        $tblCharacterMapping->modified_date = $currentDate;
        $tblCharacterMapping->created_date = $currentDate;
        //
        return $tblCharacterMapping;
    }
}
?>
