<?php
// callback class function in php
$dataList = array(
    array(
        "id" => "1.1",
        "name" => "Electronics",
        'slug' => 'furniture',
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
// Recursive function to generate the list
function generateCategoryList(array $items, callable $categoryCallback, callable $itemCallback) {
    if (empty($items)) return "";

    $html = "<ul>\n";
    foreach ($items as $item) {
        // Use category callback if it has children, otherwise use item callback
        if (!empty($item["children"])) {
            $html .= $categoryCallback($item, $categoryCallback, $itemCallback);
        } else {
            $html .= $itemCallback($item);
        }
    }
    $html .= "</ul>\n";

    return $html;
}

// Callback for parent categories
$renderCategory = function ($item, $categoryCallback, $itemCallback) {
    $name = htmlspecialchars($item["name"]);

    $html = "<li><span>" . $name . "</span>";
    $html .= generateCategoryList($item["children"], $categoryCallback, $itemCallback); // Recursive call for children
    $html .= "</li>\n";

    return $html;
};

// Callback for child items or standalone categories
$renderItem = function ($item) {
    $name = htmlspecialchars($item["name"]);
    $slug = htmlspecialchars($item["slug"]);
    $url = "/category/" . urlencode($slug);

    return "<li><a href=\"" . $url . "\">" . $name . "</a></li>";
};

// Generate and display the category list
echo generateCategoryList($dataList, $renderCategory, $renderItem);
?>
<?php
function generateListWithCallback($items, $callback) {
    if (empty($items)) {
        return "";
    }

    $html = "<ul>\n";
    foreach ($items as $item) {
        // Use the callback to generate content for each <li>
        $html .= "<li>" . $callback($item);

        // Recursively generate the list for children
        if (!empty($item["children"])) {
            $html .= generateListWithCallback($item["children"], $callback);
        }

        $html .= "</li>\n";
    }
    $html .= "</ul>\n";

    return $html;
}

// Define a callback function
$callback = function($item) {
    return htmlspecialchars($item["name"]); // Customize this as needed
};

$callback = function($item) {
    return "<span data-id=\"" . htmlspecialchars($item["id"]) . "\">" . htmlspecialchars($item["name"]) . "</span>";
};

// Generate the HTML list
$htmlList = generateListWithCallback($dataList, $callback);

// Output the list
//echo $htmlList;
?>
<?php
/*
class ListRenderer {
    public function generateList($items) {
        return $this->buildList($items, [$this, "renderItem"]);
    }

    private function buildList($items, $callback) {
        if (empty($items)) {
            return "";
        }

        $html = "<ul>\n";
        foreach ($items as $item) {
            $html .= "<li>" . call_user_func($callback, $item);
            if (!empty($item["children"])) {
                $html .= $this->buildList($item["children"], $callback);
            }
            $html .= "</li>\n";
        }
        $html .= "</ul>\n";
        return $html;
    }

    public function renderItem($item) {
        return htmlspecialchars($item["name"]);
    }
}
$data = [
    [
        "name" => "Electronics",
        "children" => [
            ["name" => "Phones", "children" => []],
            ["name" => "Laptops", "children" => []],
        ],
    ],
    ["name" => "Furniture", "children" => []],
];
$renderer = new ListRenderer();
$callback = function($item) use ($renderer) {
    return "<span>" . $renderer->renderItem($item) . "</span>";
};
echo $renderer->generateList($dataList);
*/
?>