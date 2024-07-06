<?php
namespace RzSDK\Response;
?>
<?php
enum InfoType: string {
    case ALERT      = "alert";
    case ERROR      = "error";
    case INFO      = "info";
    case MESSAGE    = "message";
    case SUCCESS    = "success";
    case WARNING    = "warning";
}
?>

