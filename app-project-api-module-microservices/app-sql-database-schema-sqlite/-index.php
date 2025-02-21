<?php
// public/index.php
//require __DIR__ . '/../vendor/autoload.php';
require_once("include.php");
?>
<?php
use App\DatabaseSchema\Data\Repositories\DatabaseSchemaRepositoryImpl;
use App\DatabaseSchema\Presentation\ViewModels\DatabaseSchemaViewModel;
use App\DatabaseSchema\Presentation\Views\DatabaseSchemaView;
?>
<?php
$repository = new DatabaseSchemaRepositoryImpl();
$viewModel = new DatabaseSchemaViewModel($repository);
$view = new DatabaseSchemaView($viewModel);

$view->render();
?>
<?php
$baseUrl = "http://localhost:8080/";
$dataList = array(
    "home.php" => "home",
    "about.php" => "about",
    "contact.php" => "contact",
    "services.php" => "services",
    "categories" => array(
        "categories.php" => "categories",
        "clothes.php" => "clothes",
        "shoes.php" => "shoes",
    ),
    "products" => array(
        "products.php" => "products",
        "hardware.php" => "hardware",
        "accessories.php" => "accessories",
        "details" => array(
            "details.php" => "details",
            "addresses.php" => "addresses",
        ),
    ),
);
class DataListToMenuBuilder {
    public function build(array $dataList, callable $callback, $path = '') {
        $data = "";
        foreach ($dataList as $key => $item) {
            $fullPath = !empty($path) ? "{$path}/{$key}" : $key;
            if(is_array($item)) {
                // Parent item
                $data .= $callback($key, $item, $fullPath, true);
                // Recursive call for child items
                $data .= $this->build($item, $callback, $fullPath);
                // Closing tag for parent item
                $data .= $callback($key, $item, $fullPath, false);
            } else {
                // Normal menu item
                $data .= $callback($key, $item, $fullPath, false);
            }
        }
        return $data;
    }
}
class BuildHtmlMenu {
    public function buildTopbarMenu(array $dataList, string $baseUrl) {
        $menuBuilder = new DataListToMenuBuilder();
        $htmlOutput = "<ul>";
        $htmlOutput .= $menuBuilder->build($dataList, function($key, $item, $path, $isParent) use($baseUrl) {
            if($isParent) {
                return "<li>" . ucfirst($key) . "<ul>";  // Parent opens a submenu
            } else if(!$isParent) {
                if(is_array($item)) {
                    return "";
                } else {
                    return "<li><a href=\"{$baseUrl}{$path}\">" . ucfirst($item) . "</a></li>";
                }
            } else {
                return "</ul></li>"; // Closing parent submenu
            }
        });
        $htmlOutput .= "</ul>";
        return $htmlOutput;
    }
    public function buildSideMenu(array $dataList, string $baseUrl) {
        $menuBuilder = new DataListToMenuBuilder();
        $htmlOutput = "<ul>";
        $htmlOutput .= $menuBuilder->build($dataList, function($item, $level, $isParent) use($baseUrl) {
            if($isParent) {
                return "<li>{$item}<ul>";
            } else {
                return "<li><a href=\"{$baseUrl}{$item}\">{$item}</a></li>";
            }
        });
        $htmlOutput .= "</ul>";
        return $htmlOutput;
    }
}
$buildHtmlMenu = new BuildHtmlMenu();
echo $buildHtmlMenu->buildTopbarMenu($dataList, $baseUrl);
?>
<!--* No html inside DataListToMenuBuilder -> build method.
* All html handle and responsible from BuildHtmlMenu class.
Output must be:
<ul>
    <li><a href="http://localhost:8080/home.php">Home</a></li>
    <li><a href="http://localhost:8080/about.php">About</a></li>
    <li><a href="http://localhost:8080/contact.php">Contact</a></li>
    <li><a href="http://localhost:8080/services.php">Services</a></li>
    <li>Categories
        <ul>
            <li><a href="http://localhost:8080/categories/categories.php">Categories</a></li>
            <li><a href="http://localhost:8080/categories/clothes.php">Clothes</a></li>
            <li><a href="http://localhost:8080/categories/shoes.php">Shoes</a></li>
        </ul>
    </li>
    <li>Products
        <ul>
            <li><a href="http://localhost:8080/products/products.php">Products</a></li>
            <li><a href="http://localhost:8080/products/hardware.php">Hardware</a></li>
            <li><a href="http://localhost:8080/products/accessories.php">Accessories</a></li>
            <li>Details
                <ul>
                    <li><a href="http://localhost:8080/products/details/details.php">Details</a></li>
                    <li><a href="http://localhost:8080/products/details/addresses.php">Addresses</a></li>
                </ul>
            </li>
        </ul>
    </li>
</ul>-->
