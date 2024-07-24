<?php
namespace RzSDK\Word\Navigation;
?>
<?php
class SideLink {
    private $baseUrl;

    public function __construct($baseUrl = BASE_URL) {
        $this->baseUrl = $baseUrl;
    }

    public function getSideLink() {
        $retValue = "";
        $sideLinkList = $this->getLinkList();
        foreach($sideLinkList as $key => $value) {
            $retValue .= ""
                . "\n<table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">"
                . "\n<tr height=\"30px\">"
                . "\n<td>"
                . "\n<a href=\"{$key}\">"
                . $value
                . "</a>"
                . "\n</td>"
                . "\n</tr>"
                . "\n</table>"
                . " ";
        }
        return $retValue;
    }

    private function getLinkList() {
        return array(
            $this->baseUrl => "Home",
            $this->baseUrl . "/language-entry/" . "language-entry.php" => "Language Entry",
            $this->baseUrl . "/word-entry/" . "word-entry.php" => "Word Entry",
            $this->baseUrl . "/word-edit/" . "word-edit.php" => "Word Edit",
            $this->baseUrl . "/word-meaning-entry/" . "word-meaning-entry.php" => "Word Meaning Entry",
            $this->baseUrl . "/word-search-module/" . "word-search-module-output.php" => "Word Search Module",
            $this->baseUrl . "/gen-database-schema.php" => "Database Table Home",
        );
    }
}
?>