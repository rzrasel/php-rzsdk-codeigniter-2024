<?php
namespace RzSDK\SqlQuery;
?>
<?php
use RzSDK\SqlQuery\InsertQuery;
?>
<?php
class SqlQueryBuilder {
    public function __construct() {}

    public function select() {
    }

    public function insert(string $table) {
        return (new InsertQuery())->setTable($table);
    }

    /*public function from() {
    }

    public function build() {
    }*/
}
?>
<?php
//https://github.com/iamludal/mysql-querybuilder
?>