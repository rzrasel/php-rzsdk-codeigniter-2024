<?php

namespace RzSDK\User;

use RzSDK\Database\SqliteConnection;

class UserRegistration
{
    private $sqlConn;
    private $dbResult;

    public function __construct($sqlConn)
    {
        $this->sqlConn = $sqlConn;
    }

    private function checkDatabase()
    {
        $this->dbResult = $this->sqlConn->query("SELECT * FROM user_registration");
        if ($this->dbResult != null) {
            echo "Database get data";
            foreach ($this->dbResult as $row) {
                $rowValue = "";
                /*foreach($this->menuTypeProperty as $key => $value) {
                    $rowValue = $row[$key] . " ";
                }*/
                echo trim($row["id"]);
                echo trim($row["modified_date"]);
                echo trim($row["create_date"]);
                echo "<br />";
            }
        } else {
            echo "Database null data";
        }
    }
}