<?php
//header("Location: php-rzsdk-project-directory/rz-microservices");
?>
<?php
global $baseUrl;
$baseUrl = "http://localhost/php-rzsdk-codeigniter";
$baseUrl = trim(trim($baseUrl, "\\"), "/");
// callback class function in php
?>
<?php
$dataList = array(
    array(
        "id" => "1.1",
        "name" => "Home",
        "slug" => "",
        "children" => array(),
    ),
    array(
        "id" => "1.2",
        "name" => "Code Comment Project Comment Formatter",
        "slug" => "project-comment-formatter",
        "children" => array(),
    ),
    array(
        "id" => "1.3",
        "name" => "Project Accent Word Book Word Language",
        "slug" => "php-rzsdk-project-directory/rz-microservices/project-accent-word-book-word-language/",
        "children" => array(),
    ),
    /*array(
        "id" => "1.4",
        "name" => "APP Project API Module Microservices",
        "slug" => "app-project-api-module-microservices",
        "children" => array(),
    ),*/
    array(
        "id" => "1.5",
        "name" => "APP Project API Module Microservices",
        "slug" => "app-project-api-module-microservices",
        "children" => array(
            array(
                "id" => "1.4",
                "name" => "APP Project API Module Microservices",
                "slug" => "",
                "children" => array(),
            ),
            array(
                "id" => "2.1",
                "name" => "App Sql Database Schema",
                "slug" => "app-sql-database-schema",
                "children" => array(),
            ),
        )
    ),
    array(
        "id" => "1.6",
        "name" => "Electronics",
        "slug" => "http://localhost/php-rzsdk-codeigniter/",
        "children" => array(
            array(
                "id" => "2.1",
                "name" => "Phones",
                "slug" => "furniture",
                "children" => array(),
            ),
            array(
                "id" => "2.2",
                "name" => "Laptops",
                "slug" => "furniture",
                "children" => array(),
            ),
        )
    ),
    array(
        "id" => "1.7",
        "name" => "Furniture",
        "slug" => "furniture",
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

// Callback for parent categories
$categoryCallback = function ($item, $generator, $parentSlug, $level) {
    $name = htmlspecialchars($item["name"]);
    $slug = htmlspecialchars($item["slug"]);
    $class = "category level-" . $level;
    //$html = "\n<li class=\"" . $class . "\"><a><span>" . $name . "</span></a>\n";
    $html = "\n<li><a><span>" . $name . "</span></a>\n";
    //$html = str_repeat("\t", ($level + 1)) . $html;
    $slug = urldecode($slug);
    $html .= str_repeat("\t", ($level + 1)) . $generator->generate($item["children"], $slug, $level + 1); // Recursive call
    $html .= "</li>\n";
    return $html;
};

// Callback for child items or standalone categories
$itemCallback = function ($item, $parentSlug, $level) {
    global $baseUrl;
    $name = htmlspecialchars($item["name"]);
    $slug = htmlspecialchars($item["slug"]);
    //$url = "/category/" . urlencode($slug);
    /*$url = urlencode($slug);
    $url = urldecode($slug);*/
    $parentPath = $parentSlug ? "/" . urlencode($parentSlug) : "";
    //$url = "/category" . $parentPath . "/" . urlencode($slug);
    $url = urldecode($baseUrl) . $parentPath . "/" . urlencode($slug);
    $class = "item level-" . $level;

    $url = urldecode($url);

    //return str_repeat("\t", ($level + 1)) . "<li class=\"" . $class . "\"><a href=\"" . $url . "\" target=\"_blank\">" . $name . "</a></li>\n";
    return str_repeat("\t", ($level + 1)) . "<li><a href=\"" . $url . "\" target=\"_blank\">" . $name . "</a></li>\n";
};

// Create an instance of the generator with callbacks
$generator = new GenerateListMenuCallback($categoryCallback, $itemCallback);

echo "<div class=\"menu\">\n";
// Generate and output the category list
echo $generator->generate($dataList);
echo "</div>\n";
?>
<style type="text/css">
    .menu {
        margin-top: 50px;
        margin-left: 10%;
        /*margin-right: 20%;*/
        width: 450px;
        background-color: #f9f9f9;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 10px;
        font-family: Arial, sans-serif;
    }
    .menu ul {
        list-style: none;
        list-style-type: none;
        margin: 0;
        padding: 0;
        overflow: hidden;
        /*background-color: #ffffff;*/
        background-color: #f9f9f9;
    }
    .menu li {
        margin: 5px 0;
    }
    .menu li a {
        display: block;
        color: #333333;
        text-align: left;
        padding: 6px 4px;
        border-radius: 6px;
        text-decoration: none;
        transition: background-color 0.3s, color 0.3s;
    }
    .menu li a:hover:not(.active) {
        /*color: #ffffff;*/
        /*color: #ffffff;*/
        color: #f9f9f9;
        /*background-color: #111111;*/
        background-color: #007BFF;
        cursor: pointer;
    }
    /*.menu li a span {
        !*display: block;
        color: #000000;*!
        font-weight: bold;
        padding: 0px 0px;
        cursor: default;
    }
    .menu li a:hover:not(.active) span {
        color: #000000;
        font-weight: bold;
        background-color: #ffffff;
        cursor: default;
    }*/
    /* Category Styles */
    .menu li > a span {
        font-weight: bold;
        cursor: pointer;
        background-color: #e9ecef;
        display: block;
        padding: 10px;
        border-radius: 6px;
        transition: background-color 0.3s, color 0.3s;
    }

    .menu li > a span:hover {
        background-color: #0056b3;
        /*color: #fff;*/
        color: #e9ecef;
        cursor: default;
    }

    /* Level Indicators */
    .menu li > a span::before {
        content: "\25B6"; /* Right-pointing triangle */
        display: inline-block;
        margin-right: 8px;
        transition: transform 0.3s;
    }

    .menu li:hover > a span::before {
        transform: rotate(90deg);
    }
</style>
<?php

?>
<?php
// Recursive function to reformat the array with sequential IDs
function reformatArray($array, $idCounter = 1) {
    $result = array();
    $childCounter = 0;
    foreach($array as $item) {
        $childCounter++;
        // Add depth information to the current item
        $newItem = array(
            "id" => "{$idCounter}.{$childCounter}",
            "name" => $item["name"],
            "slug" => $item["slug"],
            //"depth" => $depth, // Add the depth of this item
        );

        // If the item has children, recurse into them, increasing the depth
        if(!empty($item["children"])) {
            //$idCounter++;
            $newItem["children"] = reformatArray($item["children"], $idCounter + 1);
        } else {
            $newItem["children"] = array(); // Ensure children is always an array
        }

        // Add the modified item to the result array
        $result[] = $newItem;
    }
    return $result;
}

// Call the function to reformat the array
$formattedArray = reformatArray($dataList);

// Function to convert the formatted array into the requested "array()" string format
function arrayToString($array) {
    $result = "array(";
    $items = [];

    foreach($array as $item) {
        $childrenStr = empty($item["children"]) ? "array()" : arrayToString($item["children"]);
        $items[] = "array(\"id\" => \"{$item['id']}\", \"name\" => \"{$item['name']}\", \"slug\" => \"{$item['slug']}\", \"children\" => $childrenStr)";
    }

    $result .= implode(", ", $items);
    $result .= ")";
    return $result;
}

/*echo "<br />";
echo "<br />";
echo "<br />";
echo "<br />";*/
// Output the final formatted string
//echo arrayToString($formattedArray) . ";";
/*echo "<br />";
echo "<br />";
echo "<br />";
echo "<br />";*/
?>
<?php
/*echo 'array(' . PHP_EOL;
foreach ($formattedArray as $item) {
    echo '    ' . var_export($item, true) . ',' . PHP_EOL;
}
echo ');';*/
?>
<?php
/*echo "<br />";
echo "<br />";
echo "<br />";
echo "<br />";
echo "\n\n\n\n";*/
/*// Recursive function to add depth to each array element
function addDepthAsStringArray($array, $depth = 1) {
    $result = array();

    $counter = 0;
    foreach($array as $item) {
        $counter++;
        $itemId = "$depth.$counter";
        // Add depth information to the current item
        // Create the item string with depth and children in array() format
        $newItem = "array(";
        $newItem .= "\"id\" => \"$itemId\", ";
        $newItem .= "\"name\" => \"$item[name]\", ";
        $newItem .= "\"slug\" => \"$item[slug]\", ";
        //$newItem .= "\"depth\" => \"$depth\", "; // Add depth in string format
        $newItem .= "\"children\" => ";

        // If the item has children, recurse into them, increasing the depth
        if(!empty($item["children"])) {
            //$newItem["children"] = addDepthToArray($item["children"], $depth + 1);
            //$newItem .= addDepthAsStringArray($item["children"], $depth . "." . count($item["children"]) + 1); // Increase depth for children
            $newItem .= addDepthAsStringArray($item["children"], $depth + 1);
        } else {
            //$newItem["children"] = array(); // Ensure children is always an array
            $newItem .= "array()"; // If no children, empty array
        }

        $newItem .= ")";
        // Add the modified item to the result array
        $result[] = $newItem;
    }

    return $result;
    //return implode(", ", $result); // Return all items in a single string
}

// Call the function to add depth and transform the array
$transformedArray = addDepthAsStringArray($dataList);

// Output the transformed array with depth
//echo "<pre>" . print_r($transformedArray, true) . "</pre>";
echo "<pre>" . "array($transformedArray)" . "</pre>";*/
/*function reformatArrayPrint($array, $parentCounter = 1) {
    $childCounter = 0;
    echo "array(" . PHP_EOL;
    foreach($array as $item) {
        $childCounter++;
        $itemId = "$parentCounter.$childCounter";
        //
        //
        $newItem = "array(";
        $newItem .= "\"id\" => \"$itemId\", ";
        $newItem .= "\"name\" => \"$item[name]\", ";
        $newItem .= "\"slug\" => \"$item[slug]\", ";
        //$newItem .= "\"depth\" => \"$depth\", "; // Add depth in string format
        $newItem .= "\"children\" => ";
        //
        //
        if(!empty($item["children"])) {
            $newItem .= reformatArrayPrint($item["children"], $parentCounter + 1);
        } else {
            $item["id"] = "{$parentCounter}.{$childCounter}";
            //echo "\t" . var_export($item, true) . "," . PHP_EOL;
            $newItem .= "array()";
            echo "\t" . $newItem . "," . PHP_EOL;
            //echo ")," . PHP_EOL;
        }
        echo ")," . PHP_EOL;
    }
    echo ")," . PHP_EOL;
}
echo "\$dataList = ";
reformatArrayPrint($dataList);
echo ";";
echo "\n\n\n\n";
echo "<br />";
echo "<br />";
echo "<br />";
echo "<br />";*/
echo "<br />";
echo "<br />";
echo "<br />";
echo "<br />";
function reformatArrayTwo($items, $parentId = 1) {
    $result = array();
    $childId = 0;
    foreach ($items as $item) {
        $childId++;
        $newId = "$parentId.$childId";
        $newItem = array(
            "id" => $newId,
            "name" => $item["name"],
            "slug" => $item["slug"],
            "children" => array()
        );

        if (!empty($item["children"])) {
            $childCounter = 1; // Reset for child level
            $newItem["children"] = reformatArrayTwo($item["children"], $childCounter + 1);
        }

        $result[] = $newItem;
        //$idCounter++;
    }
    return $result;
}

// Format the array as a string in "array()" format
function formatArrayAsString($array, $indent = 0) {
    $output = str_repeat("    ", $indent) . "array(\n";
    foreach ($array as $item) {
        $output .= str_repeat("    ", $indent + 1) . "array(\n";
        $output .= str_repeat("    ", $indent + 2) . '"id" => "' . $item['id'] . '",' . "\n";
        $output .= str_repeat("    ", $indent + 2) . '"name" => "' . $item['name'] . '",' . "\n";
        $output .= str_repeat("    ", $indent + 2) . '"slug" => "' . $item['slug'] . '",' . "\n";
        $output .= str_repeat("    ", $indent + 2) . '"children" => ';

        if (empty($item['children'])) {
            $output .= "array(),\n";
        } else {
            $output .= formatArrayAsString($item['children'], $indent + 2);
        }

        $output .= str_repeat("    ", $indent + 1) . "),\n";
    }
    $output .= str_repeat("    ", $indent) . ")";
    if ($indent === 0) {
        $output .= ";";
    }
    $output .= "\n";

    return $output;
}

echo "\n\n\n\n";
$reformattedArray = reformatArrayTwo($dataList);
echo "\$dataList = " . formatArrayAsString($reformattedArray);
echo "\n\n\n\n";
echo "<pre>\n\n";
//echo "\$dataList = array(\n$transformedArray);";
echo "\n\n</pre>";
echo "<br />";
echo "<br />";
echo "<br />";
echo "<br />";
?>
<?php

?>