<?php
namespace App\Utils\Menu\Builder;
?>
<?php
class HtmlMenuDataList {
    public function sampleDataList() {
        return array(
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
    }
}
?>