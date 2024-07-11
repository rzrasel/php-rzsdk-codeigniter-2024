<?php
namespace RzSDK\Response;
?>
<?php
enum InfoType: string {
    case ALERT      = "alert";
    case DB_DATA_NOT_FOUND  = "db_data_not_found";
    case ERROR      = "error";
    case INFO      = "info";
    case MESSAGE    = "message";
    case NOT_FOUND  = "not_found";
    case SUCCESS    = "success";
    case WARNING    = "warning";
}
?>

