<?php
namespace RzSDK\Database;
?>
<?php
use RzSDK\Database\DbColumnProperties;
use RzSDK\Database\DbColumnConstraintsProperties;
?>
<?php
class DbTableProperty {
    public $tablePrefix;
    public $table;
    public $columnProperties;
    public $constraintsProperties;

    public function __construct($table = "", $tablePrefix = "") {
        $this->tablePrefix = trim(trim($tablePrefix), "_");
        $this->table = trim(trim($table), "_");
    }

    public function setColumProperty(DbColumnProperties $columnProperty): self {
        $this->columnProperties[] = $columnProperty;
        return $this;
    }

    public function setConstraintProperty(DbColumnConstraintsProperties $constraintProperty): self {
        $this->constraintsProperties[] = $constraintProperty;
        return $this;
    }

    public function getCastColumProperty(DbColumnProperties $columnProperty): DbColumnProperties {
        return $columnProperty;
    }

    public function getCastConstraintProperty(DbColumnConstraintsProperties $constraintProperty): DbColumnConstraintsProperties {
        return $constraintProperty;
    }
}
?>
<?php
/*
$dbTableProperty = (new DbTableProperty(parent::$table))
    ->setColumProperty(new DbColumnProperties("user_id", "BIGINT(20) NOT NULL"))
    ->setColumProperty(new DbColumnProperties("user_id", "BIGINT(20) NOT NULL"))
    ->setColumProperty(new DbColumnProperties("user_id", "BIGINT(20) NOT NULL"))
    ->setConstraintProperty(new DbColumnConstraintsProperties(DbColumnConstraintType::PRIMARY_KEY, "user_id"));
*/
?>
