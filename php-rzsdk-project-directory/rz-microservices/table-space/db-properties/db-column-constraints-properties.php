<?php
namespace RzSDK\DatabaseSpace;
?>
<?php
use RzSDK\Log\DebugLog;
?>
<?php
class DbColumnConstraintsProperties {
    public DbColumnConstraintType $constraintType;
    //public $primaryTable;
    public $primaryColumns;
    public $referenceTable;
    public $referenceColumn;

    public function __construct(DbColumnConstraintType $constraintType, $primaryColumns, $referenceTable = "", $referenceColumn = "") {
        $this->constraintType = $constraintType;
        //$this->primaryTable = $primaryTable;
        $this->primaryColumns = $primaryColumns;
        $this->referenceTable = $referenceTable;
        $this->referenceColumn = $referenceColumn;
    }
}
?>