<?php
namespace RzSDK\DatabaseSpace;
?>
<?php
use RzSDK\DatabaseSpace\DbColumnProperties;
use RzSDK\DatabaseSpace\DbColumnConstraintsProperties;
?>
<?php
class DbTableProperty {
    public $table;
    public $columnProperties;
    public $constraintsProperties;

    public function __construct($table = "") {
        $this->table = $table;
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
