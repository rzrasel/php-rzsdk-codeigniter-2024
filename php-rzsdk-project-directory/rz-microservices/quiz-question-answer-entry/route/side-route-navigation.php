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
            $this->baseUrl . "/language-entry/" . "language-entry.php" => "Language Entry",
            // Category Router
            $this->baseUrl . "/category-token-entry/" . "category-token-entry.php" => "Category Token Entry",
            $this->baseUrl . "/category-info-entry/" . "category-info-entry.php" => "Category Info Entry",
            // Sub Category Router
            $this->baseUrl . "/sub-category-token-entry/" . "category-token-entry.php" => "<s>Sub Category Token Entry</s>",
            $this->baseUrl . "/sub-category-info-entry/" . "sub-category-info-entry.php" => "<s>Sub Category Info Entry</s>",
            //
            $this->baseUrl . "/word-entry/" . "author-entry.php" => "<s>Author Entry</s>",
            $this->baseUrl . "/word-entry/" . "publisher-entry.php" => "<s>Publisher Entry</s>",
            // Book Router
            $this->baseUrl . "/book-token-entry/" . "book-token-entry.php" => "Book Token Entry",
            $this->baseUrl . "/book-name-entry/" . "book-name-entry.php" => "Book Name Entry",
            $this->baseUrl . "/book-info-entry/" . "book-info-entry.php" => "Book Info Entry",
            //
            $this->baseUrl . "/word-entry/" . "sectioning-entry.php" => "Sectioning Entry",
            $this->baseUrl . "/word-entry/" . "word-entry.php" => "Content Entry",
            /*$this->baseUrl . "/word-edit/" . "word-edit.php" => "Word Edit",
            $this->baseUrl . "/word-meaning-entry/" . "word-meaning-entry.php" => "Word Meaning Entry",
            $this->baseUrl . "/word-search-module/" . "word-search-module-output.php" => "Word Search Module",*/
            // Database Schema Generator
            $this->baseUrl . "/pages/generate-database-schema.php" => "Database Table Home",
        );
    }
}
?>