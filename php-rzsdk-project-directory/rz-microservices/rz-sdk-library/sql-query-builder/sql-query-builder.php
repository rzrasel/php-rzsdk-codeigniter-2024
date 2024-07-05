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

    public function select(string $table = "", array $columns = array()) {
        //return (new SelectQuery())->select($table, $columns);
        return new SelectQuery($table, $columns);
    }

    public function insert(string $table) {
        //return (new InsertQuery())->setTable($table);
        return new InsertQuery($table);
    }

    public function update(string $table) {
        return new UpdateQuery($table);
    }

    public function delete(string $table) {
        return new DeleteQuery($table);
    }
}
?>
<?php
//$sqlQueryBuilder = new SqlQueryBuilder();
//https://github.com/iamludal/mysql-querybuilder
//https://github.com/nilportugues/php-sql-query-builder/tree/master
//https://learnsql.com/blog/how-to-use-aliases-with-sql-join/
?>