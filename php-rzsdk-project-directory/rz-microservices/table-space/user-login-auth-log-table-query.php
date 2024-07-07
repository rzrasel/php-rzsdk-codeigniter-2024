<?php
namespace RzSDK\DatabaseSpace;
?>
<?php
class UserLoginAuthLogTableQuery extends UserLoginAuthLogTable {
    public function __construct() {
        //$this->execute(DbType::SQLITE);
    }

    public function dropQuery(DbType $dbType) {
        $table = parent::$table;
        return "DROP TABLE IF EXISTS " . $table . ";";
    }

    public function deleteQuery(DbType $dbType) {
        $table = parent::$table;
        return "DELETE FROM " . $table . ";";
    }
}
?>