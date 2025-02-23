<?php
namespace RzSDK\Database;
?>
<?php
?>
<?php
class DbColumnProperties {
    public $column;
    public $property;

    public function __construct($column, $property) {
        $this->column = trim(trim($column), "_");
        $this->property = trim(trim($property), "_");
    }
}
?>