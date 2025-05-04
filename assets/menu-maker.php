<?php
class GenerateListMenuCallback {
    private $categoryCallback;
    private $itemCallback;

    // Constructor to initialize the callbacks
    public function __construct(callable $categoryCallback, callable $itemCallback) {
        $this->categoryCallback = $categoryCallback;
        $this->itemCallback = $itemCallback;
    }

    // Public method to start the recursive generation
    public function generate(array $categories, string $parentSlug = "", int $level = 0) {
        return $this->buildList($categories, $parentSlug, $level);
    }

    // Recursive function to build the <ul> <li> structure
    private function buildList(array $items, string $parentSlug, int $level) {
        if(empty($items)) return "";

        //$html = "<ul class=\"level-" . $level . "\">\n";
        $html = "<ul>\n";
        foreach($items as $item) {
            $currentSlug = $item["slug"] ?? "";
            //$parentSlug = $item["slug"] ?? "";
            // Use category callback if it has children, otherwise use item callback
            if(!empty($item['children'])) {
                $html .= call_user_func($this->categoryCallback, $item, $this, $parentSlug, $level);
            } else {
                $html .= call_user_func($this->itemCallback, $item, $parentSlug, $level);
            }
        }
        $html .= str_repeat("\t", $level) . "</ul>\n";

        return $html;
    }
}
?>