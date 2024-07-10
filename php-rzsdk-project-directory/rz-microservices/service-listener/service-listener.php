<?php
namespace RzSDK\Service\Listener;
?>
<?php
interface ServiceListener {
    function onError($dataSet, $message);
    //function onFailure($dataSet, $message);
    function onSuccess($dataSet, $message);
}
?>