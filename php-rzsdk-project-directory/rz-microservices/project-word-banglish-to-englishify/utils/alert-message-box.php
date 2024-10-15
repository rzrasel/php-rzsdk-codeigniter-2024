<?php
namespace RzSDK\Utils\Alert\Message;
?>
<?php
class AlertMessageBox {
    public function __construct() {}

    public function build($message) {
        return "<div style=\"{$this->getGreenCss()}\">"
            . $message
            . "</div>";
    }
    private function getGreenCss() {
        return "line-height: 16px; margin: auto; background: #4eaf51; color: #fdfdf9; border: 1px solid #3a833d; padding: 10px; border-radius: 10px;";
    }
}
?>
