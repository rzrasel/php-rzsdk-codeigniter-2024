<?php
namespace RzSDK\Book\Navigation\Route;
?>
<?php
class SideRoureNavigation {
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
            $this->baseUrl => "Home",
            $this->baseUrl . "/language-entry/" . "language-entry.php" => "Language Entry",
            $this->baseUrl . "/language-entry/" . "religion-entry.php" => "Religion Entry",
            $this->baseUrl . "/word-entry/" . "author-entry.php" => "Author Entry",
            $this->baseUrl . "/word-entry/" . "book-entry.php" => "Book Entry",
            $this->baseUrl . "/word-entry/" . "section-entry.php" => "Section Entry",
            $this->baseUrl . "/word-entry/" . "word-entry.php" => "Content Entry",
            $this->baseUrl . "/word-edit/" . "word-edit.php" => "Word Edit",
            $this->baseUrl . "/word-meaning-entry/" . "word-meaning-entry.php" => "Word Meaning Entry",
            $this->baseUrl . "/word-search-module/" . "word-search-module-output.php" => "Word Search Module",
            $this->baseUrl . "/pages/generate-database-schema.php" => "Database Table Home",
        );
    }
}
?>