<?php
namespace App\Microservice\Schema\Domain\Model\Language;
?>
<?php
class LanguageEntity {
    public $id;
    public $name;
    public $iso_code_2;
    public $iso_code_3;
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