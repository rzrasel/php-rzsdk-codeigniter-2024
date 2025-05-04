<?php
require_once("assets/menu-data-list.php");
require_once("assets/menu-maker.php");
//header("Location: php-rzsdk-project-directory/rz-microservices");
?>
<?php
global $baseUrl;
$baseUrl = "http://localhost/php-rzsdk-codeigniter";
$baseUrl = trim(trim($baseUrl, "\\"), "/");
// callback class function in php
?>
<?php
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
    global $homeName;
    //echo "<pre>" . print_r($item, true) . "</pre>";
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

    $target = " target=\"_blank\"";
    if($name == $homeName) {
        $target = "";
    }

    //return str_repeat("\t", ($level + 1)) . "<li class=\"" . $class . "\"><a href=\"" . $url . "\" target=\"_blank\">" . $name . "</a></li>\n";
    return str_repeat("\t", ($level + 1)) . "<li><a href=\"" . $url . "\"$target>" . $name . "</a></li>\n";
};

// Create an instance of the generator with callbacks
$generator = new GenerateListMenuCallback($categoryCallback, $itemCallback);

echo "<div class=\"menu\">\n";
// Generate and output the category list
global $dataList;
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
?>
<?php
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