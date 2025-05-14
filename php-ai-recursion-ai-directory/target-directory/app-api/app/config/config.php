<?php
define("DB_TYPE", "mysql"); // Set "sqlite" for SQLite or "mysql" for MySQL
define("DB_HOST", "localhost");
define("DB_NAME", "test_db");
define("DB_USER", "root");
define("DB_PASS", "");

// SQLite Configuration (for SQLite database)
define("DB_SQLITE_PATH", __DIR__ . "/database.db");

defined("BASE_URL") or define("BASE_URL", "http://localhost/app-api/");
// Default Controller and Action
define("DEFAULT_CONTROLLER", "HomeController");
define("DEFAULT_ACTION", "index");

// Error Reporting
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>