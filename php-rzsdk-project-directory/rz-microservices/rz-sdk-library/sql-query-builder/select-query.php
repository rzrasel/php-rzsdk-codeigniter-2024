<?php
namespace RzSDK\SqlQueryBuilder;
?>
<?php
use RzSDK\Utils\ArrayUtils;
use RzSDK\SqlQueryBuilder\SelectColumnSql;
use RzSDK\SqlQueryBuilder\SelectFromTableSql;
use RzSDK\SqlQueryBuilder\SelectJoinSql;
use RzSDK\SqlQueryBuilder\SelectWhereSql;
use RzSDK\SqlQueryBuilder\SelectOrderBySql;
use RzSDK\SqlQueryBuilder\SelectLimitSql;
use RzSDK\SqlQueryBuilder\SelectOffsetSql;
use RzSDK\Log\DebugLog;
?>
<?php
class SelectQuery {
    //
    use SelectColumnSql;
    use SelectFromTableSql;
    use SelectJoinSql;
    use SelectWhereSql;
    use SelectOrderBySql;
    use SelectLimitSql;
    use SelectOffsetSql;
    //
    private $sqlQuery;
    //

    public function build() {
        $this->sqlQuery = "SELECT"
            . " {$this->toSelectStatement()}"
            . " FROM {$this->toFromTableStatement()}"
            . " {$this->toInnerJoinStatement()}"
            . " {$this->toWhereStatement()}"
            . " {$this->toOrderByStatement()}"
            . " {$this->toLimitStatement()}"
            . " {$this->toOffsetStatement()}"
            . " ";
        $this->sqlQuery = preg_replace("/\s+/u", " ", $this->sqlQuery);
        return trim($this->sqlQuery) . ";";
    }
}
?>
<?php
?>
<?php
/*$sqlQueryBuilder = new SqlQueryBuilder();
//$sqlQuery = $sqlQueryBuilder->select(array("user_id", "user_email"))->build();
//$sqlQuery = $sqlQueryBuilder->select(array("user_id" => "id", "user_email" => "email"))->build();
$sqlQuery = $sqlQueryBuilder->select("",
    array(
        "user" => array(
            "user_id" => "id",
            "email" => "email",
        ),
        "user_password" => array(
            "user_id" => "pid",
            "password" => "password",
        )
    ))
    ->from(array("user_info" => "user"))
    //->from("user_info")
    //->innerJoin(array("user_info", "user_password"), array("email", "email"))
    ->innerJoin(
        array("user_info" => "user"), array("user_password" => "password"),
        "user_id", "user_id")
    ->where("",
        array(
            "user" => array("email = 'email@gmail.com'", "status = TRUE",)
        ), true)
    ->orderBy("user.modified_date", "ASC")
    ->limit(10)
    ->offset(5)
    ->build();
DebugLog::log($sqlQuery);
$sqlQuery = $sqlQueryBuilder->select("",
    array(
        "user" => array(
            "user_id" => "id",
            "email" => "email",
        ),
        "user_password" => array(
            "user_id" => "pid",
            "password" => "password",
        )
    ))
    ->from(array("user_info" => "user"))
    //->from("user_info")
    //->innerJoin(array("user_info", "user_password"), array("email", "email"))
    ->innerJoin(
        array("user_info" => "user"), array("user_password" => "user_password"),
        "user_id", "user_id")
    ->where("",
        array(
            "user" => array("email = 'email@gmail.com'", "status = TRUE",)
        ), true)
    ->orderBy("user.modified_date", "ASC")
    ->limit(10)
    ->offset(5)
    ->build();
DebugLog::log($sqlQuery);
$sqlQuery = $sqlQueryBuilder->select( "",
    array(
        "user" => array(
            "user_id" => "id",
            "email" => "email",
        ),
        "user_password" => array(
            "user_id" => "pid",
            "password" => "password",
        )
    ))
    ->from(array("user_info" => "user"))
    //->from("user_info")
    //->innerJoin(array("user_info", "user_password"), array("email", "email"))
    ->innerJoin(
        array("user_info" => "user"), array("user_password" => "user_password"),
        "user_id", "user_id")
    ->where("",
        array(
            "user" => array("email = 'email@gmail.com'", "status = TRUE",)
        ), true)
    ->orderBy("user.modified_date", "ASC")
    ->limit(10)
    ->offset(5)
    ->build();
DebugLog::log($sqlQuery);
$sqlQuery = $sqlQueryBuilder
    ->select( "user",
        array(
            "user_id" => "id",
            "email" => "email",
        )
    )
    ->select("password",
        array(
            "user_id",
            "password",
        )
    )
    ->from("user_info", "user")
    ->where("user",
        array(
            "user_id_user_info = 1111",
            "email_user_info = 2222"
        )
    )
    ->where("password",
        array(
            "user_id_password = 3333",
            "password_password = 4444"
        ), false
    )
    ->build();
DebugLog::log($sqlQuery);*/
?>
