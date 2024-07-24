<?php
namespace RzSDK\Database;
?>
<?php
enum DbColumnConstraintType: string {
    case FOREIGN_KEY    = "FOREIGN KEY";
    case PRIMARY_KEY    = "PRIMARY KEY";
    case UNIQUE         = "UNIQUE";
}
?>