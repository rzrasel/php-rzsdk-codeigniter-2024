<?php
namespace App\Utils\Menu\Builder;
?>
<?php
class HtmlMenuDataList {
    public static function sampleDataList() {
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

    public static function sqlDatabaseDataList() {
        return array(
            "Data Entry" => array(
                "upper-level-01" => "upper-level-01",
                "database-schema-entry-view.php" => "Database Schema Entry",
                "table-data-entry-view.php" => "Table Data Entry",
                "column-data-entry-view.php" => "Column Data Entry",
                "column-key-entry-view.php" => "Column Key Entry",
                "composite-key-entry-view.php" => "Composite Key Entry",
            ),
            "Edit Update" => array(
                "upper-level-01" => "upper-level-01",
                "column-data-update-view.php" => "Column Data Update",
            ),
            "Database Schema Output" => array(
                "upper-level-01" => "upper-level-01",
                "database-schema-statement-query-view.php" => "Database Schema Statement Query",
            ),
        );
    }
}
?>