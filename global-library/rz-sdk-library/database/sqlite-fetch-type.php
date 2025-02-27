<?php
namespace RzSDK\Database;
?>
<?php
use PDO;
?>
<?php
enum SqliteFetchType: int {
    case FETCH_ASSOC = PDO::FETCH_ASSOC;
    case FETCH_DEFAULT = PDO::FETCH_DEFAULT;
    case FETCH_OBJ = PDO::FETCH_OBJ;
    case NONE = -99;
}
?>
