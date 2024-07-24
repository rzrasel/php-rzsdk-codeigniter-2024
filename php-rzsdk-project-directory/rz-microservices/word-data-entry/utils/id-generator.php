<?php
namespace RzSDK\Utils\Id\Generator;
?>
<?php
class IdGenerator {
    public static function getSysUserId() {
        $sysUser = "DictionaryWordDatabaseSystemUser";
        $sysUser = $sysUser . strrev($sysUser);
        $sysUser = $sysUser . strtolower($sysUser) . strtoupper($sysUser);
        //echo $sysUser;
        $sysUserId = 0;
        for($i = 0; $i < strlen($sysUser); $i++) {
            $sysUserId += ord($sysUser[$i]);
        }
        $sysUserId = $sysUserId . "" . ($sysUserId * 2) . "" . ($sysUserId * 3);
        return $sysUserId;
    }
}