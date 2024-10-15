<?php
namespace RzSDK\DatabaseSpace;
?>
<?php
?>
<?php
class DbColumnProperties {
    public $column;
    public $property;

    public function __construct($column, $property) {
        $this->column = $column;
        $this->property = $property;
    }
}
?>