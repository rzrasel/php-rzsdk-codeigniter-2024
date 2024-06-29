<?php
namespace RzSDK\SqlQueryBuilder;
?>
<?php
use RzSDK\SqlQueryBuilder\InsertQuery;
use RzSDK\SqlQueryBuilder\SelectQuery;
?>
<?php
class SqlQueryBuilder {
    public function __construct() {}

    public function select(array $columns = array()) {
        return (new SelectQuery())->setColumns($columns);
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
//https://github.com/nilportugues/php-sql-query-builder/tree/master
?>