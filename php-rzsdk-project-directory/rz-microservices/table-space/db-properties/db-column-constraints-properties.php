<?php
namespace RzSDK\DatabaseSpace;
?>
<?php
use RzSDK\DatabaseSpace\DbType;
use RzSDK\DatabaseSpace\UserPasswordTable;
use RzSDK\Log\DebugLog;
?>
<?php
class DbColumnConstraintsProperties {
    public DbColumnConstraintType $constraintType;
    public $primaryTable;
    public $primaryColumns;
    public $referenceTable;
    public $referenceColumn;
}
?>