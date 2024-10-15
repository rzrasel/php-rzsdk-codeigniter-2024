<?php
namespace RzSDK\Utils\Id\Generator;
?>
<?php
class IdGenerator {
    public static function getSysUserId() {
        $sysUser = "ReligiousBookSystemReligiousBookSystemUser";
        $sysUser = $sysUser . strrev($sysUser);
        $sysUser = $sysUser . strtolower($sysUser) . strtoupper($sysUser);
        //echo $sysUser;
        $sysUserId = 0;
        for($i = 0; $i < strlen($sysUser); $i++) {
            $sysUserId += ord($sysUser[$i]);
        }
        $sysUserId = $sysUserId . "" . ($sysUserId * 2) . "" . ($sysUserId * 3);
        /*echo strlen($sysUserId);
        echo "<br />";
        echo $sysUserId;
        echo "<br />";*/
        return substr($sysUserId, 0, 16);
    }
}
?>