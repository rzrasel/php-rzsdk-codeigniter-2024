<?php
namespace App\Microservice\Schema\Data\Model\Language;
?>
<?php
class LanguageModel {
    public $id;
    public $name;
    public $isoCode2;
    public $isoCode3;
    public $slug;

    public function getVarList() {
        $result = array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );
        return array_keys($result);
    }

    public function getVarListWithKey() {
        return array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );
    }
}
?>