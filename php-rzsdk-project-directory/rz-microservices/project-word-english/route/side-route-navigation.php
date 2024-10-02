<?php
namespace RzSDK\Book\Navigation\Route;
?>
<?php
class SideRouteNavigation {
    private $baseUrl;

    public function __construct($baseUrl = BASE_URL) {
        $this->baseUrl = $baseUrl;
    }

    public function getSideNavigation() {
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
            // Language Router
            $this->baseUrl => "Home",
            $this->baseUrl . "/word-meaning-entry/" . "word-meaning-entry.php" => "Word Meaning Entry",
            $this->baseUrl . "/word-meaning-edit/" . "word-meaning-edit.php" => "Word Meaning Edit",
            // Category Router
            $this->baseUrl . "/word-meaning-search/" . "word-meaning-search.php" => "Word Meaning Search",
            $this->baseUrl . "/word-meaning-pronunciation/" . "word-meaning-pronunciation.php" => "Word Meaning Pronunciation",
            $this->baseUrl . "/word-meaning-side-by-side/" . "word-meaning-side-by-side.php" => "Word Meaning Side By Side",
            $this->baseUrl . "/word-meaning-new-line/" . "word-meaning-new-line.php" => "Word Meaning New Line",
            // Database Schema Generator
            $this->baseUrl . "/pages/generate-database-schema.php" => "Database Table Home",
        );
    }
}
?>