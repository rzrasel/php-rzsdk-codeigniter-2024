<?php
// callback class function in php
$dataList = array(
    array(
        "id" =>"1.2",
        "name" => "Home",
        'slug' => 'http://localhost/php-rzsdk-codeigniter/',
        "children" => array(),
    ),
    array(
        "id" => "1.1",
        "name" => "Electronics",
        'slug' => 'http://localhost/php-rzsdk-codeigniter/',
        "children" => array(
            array(
                "id" => "2.1",
                "name" => "Phones",
                'slug' => 'furniture',
                "children" => array()
            ),
            array(
                "id" => "2.2",
                "name" => "Laptops",
                'slug' => 'furniture',
                "children" => array()
            ),
        ),
    ),
    array(
        "id" =>"1.2",
        "name" => "Furniture",
        'slug' => 'furniture',
        "children" => array(),
    ),
);
?>
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
    public function generate(array $categories, int $level = 0) {
        return $this->buildList($categories, $level);
    }

    // Recursive function to build the <ul> <li> structure
    private function buildList(array $items, int $level) {
        if (empty($items)) return '';

        $html = "<ul>\n";
        foreach ($items as $item) {
            // Use category callback if it has children, otherwise use item callback
            if (!empty($item['children'])) {
                $html .= call_user_func($this->categoryCallback, $item, $this, $level);
            } else {
                $html .= call_user_func($this->itemCallback, $item, $level);
            }
        }
        $html .= "</ul>\n";

        return $html;
    }
}

// Callback for parent categories
$categoryCallback = function ($item, $generator, $level) {
    $name = htmlspecialchars($item['name']);
    $class = "category level-" . $level;
    $html = "<li class=\"" . $class . "\"><a><span>" . $name . "</span></a>";
    $html .= $generator->generate($item["children"], $level + 1); // Recursive call
    $html .= "</li>\n";
    return $html;
};

// Callback for child items or standalone categories
$itemCallback = function ($item, $level) {
    $name = htmlspecialchars($item["name"]);
    $slug = htmlspecialchars($item["slug"]);
    //$url = "/category/" . urlencode($slug);
    $url = urlencode($slug);
    $url = urldecode($slug);
    $class = "item level-" . $level;

    return "<li class=\"" . $class . "\"><a href=\"" . $url . "\">" . $name . "</a></li>\n";
};

// Create an instance of the generator with callbacks
$generator = new GenerateListMenuCallback($categoryCallback, $itemCallback);

// Generate and output the category list
echo $generator->generate($dataList);
?>