<?php
class ConvertCaseToFileName {
    public function isCamelCase($string) {
        return preg_match("/^[a-z]+([A-Z][a-z0-9]*)*$/", $string);
    }

    public function isPascalCase($string) {
        return preg_match("/^[A-Z][a-z0-9]+(?:[A-Z][a-z0-9]*)*$/", $string) === 1;
    }

    public function isUpperPascalCase($string) {
        return preg_match("/^(?:[a-z]+|[A-Z][a-z0-9]*)?(?:[A-Z]+[a-z0-9]*)+$/", $string) === 1;
    }

    public function fromCamelCase($string, $replaceBy = "-") {
        $retVal = preg_replace("/([A-Z])/", $replaceBy . "$1", $string);
        return trim($retVal, $replaceBy);
    }

    public function fromUpperPascalCase($string, $replaceBy = "-") {
        return preg_replace("/([a-z])([A-Z])|([A-Z]+)([A-Z][a-z])/", "$1$3{$replaceBy}$2$4", $string);
    }

    public function toFileName($string, $replaceBy = "-") {
        $fileNameList = array();
        $fileNameList[] = $string;
        $fileNameList[] = strtolower($string);
        $separator = $this->getSeparator();
        if($this->isCamelCase($string) || $this->isPascalCase($string)) {
            foreach($separator as $item) {
                $data = $this->fromCamelCase($string, $item);
                $fileNameList[] = $data;
                $fileNameList[] = strtolower($data);
            }
        }
        if($this->isUpperPascalCase($string)) {
            foreach($separator as $item) {
                $data = $this->fromUpperPascalCase($string, $item);
                $fileNameList[] = $data;
                $fileNameList[] = strtolower($data);
            }
        }
        return $fileNameList;
    }

    private function getSeparator() {
        return array(
            " ",
            "-",
            "_",
        );
    }
}
?>
<?php
$data = (new ConvertCaseToFileName())->toFileName("isCheckTODORequest");
echo print_r($data, true);
?>
