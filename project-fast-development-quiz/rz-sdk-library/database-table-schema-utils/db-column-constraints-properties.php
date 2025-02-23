<?php
namespace RzSDK\Database;
?>
<?php
use RzSDK\Log\DebugLog;
?>
<?php
class DbColumnConstraintsProperties {
    public DbColumnConstraintType $constraintType;
    //public $primaryTable;
    public $primaryColumns;
    public $tablePrefix;
    public $referenceTable;
    public $referenceColumn;

    public function __construct(DbColumnConstraintType $constraintType, $primaryColumns, $referenceTable = "", $tablePrefix = "", $referenceColumn = "") {
        $this->constraintType = $constraintType;
        //$this->primaryTable = $primaryTable;
        $this->primaryColumns = trim(trim($primaryColumns), "_");
        $this->tablePrefix = trim(trim($tablePrefix), "_");
        $this->referenceTable = trim(trim($referenceTable), "_");
        $this->referenceColumn = trim(trim($referenceColumn), "_");
    }
}
?>