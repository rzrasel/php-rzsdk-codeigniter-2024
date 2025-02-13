<?php
namespace RzSDK\Autoloader;
?>
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

    public function isMixedSnakeCase($string) {
        return preg_match("/^[a-z]+(_[a-z]+)*(_[A-Z][a-z]*)*$/", $string);
    }

    public function isSnakeCase($string) {
        return preg_match("/^[a-z]+(_[a-z]+)*$/", $string);
    }

    public function isComplexCaseOld($string) {
        //return preg_match("/^[a-z]+(?:_[a-z]+)*(?:_[A-Z][a-zA-Z]*)?$/", $string);
        return preg_match("/^([A-Z]?[a-z]+)_([a-z]+)(?:_([A-Z][a-z]*|[a-z]+|[A-Z]+[a-z]*|[A-Z]+[a-z]*[A-Z][a-z]*))*$/", $string);
    }

    public function isComplexCase($text) {
        // Define regex patterns for different cases
        $patterns = [
            'PascalCase' => '/^[A-Z][a-z]+(?:[A-Z][a-z]+)*$/', // e.g., ThiIsText
            'camelCase' => '/^[a-z]+(?:[A-Z][a-z]+)*$/',       // e.g., thiIsText
            'snake_case' => '/^[a-z]+(?:_[a-z]+)*$/',          // e.g., thi_is_text
            'PascalCase_with_snake' => '/^[A-Z][a-z]+(?:[A-Z][a-z]+)*(?:_[A-Z][a-z]+)*$/', // e.g., ThiIsText_SampleText
            'camelCase_with_snake' => '/^[a-z]+(?:[A-Z][a-z]+)*(?:_[a-z]+)*$/',            // e.g., thiIsText_sampleText
            'PascalCase_with_snake_and_TODO' => '/^[A-Z][a-z]+(?:[A-Z][a-z]+)*(?:_[A-Z][a-z]+)*(?:TODO[a-z]+)*$/', // e.g., ThiIsText_SampleTODOtext
        ];

        // Check the text against each pattern
        foreach ($patterns as $case => $pattern) {
            if (preg_match($pattern, $text)) {
                return true;
            }
        }

        return false;
    }

    public function fromCamelCase($string, $replaceBy = "-") {
        $retVal = preg_replace("/([A-Z])/", $replaceBy . "$1", $string);
        return trim($retVal, $replaceBy);
    }

    public function fromUpperPascalCase($string, $replaceBy = "-") {
        return preg_replace("/([a-z])([A-Z])|([A-Z]+)([A-Z][a-z])/", "$1$3{$replaceBy}$2$4", $string);
    }

    public function convertSnakeToKebab($string, $replaceBy = "-") {
        return preg_replace("/_/", $replaceBy, $string);
    }

    public function convertToHyphenCase($string, $replaceBy = "-") {
        // Step 1: Replace underscores with hyphens
        $text = str_replace("_", $replaceBy, $string);

        // Step 2: Add hyphen before uppercase letters that follow lowercase letters
        $text = preg_replace("/([a-z])([A-Z])/", "$1{$replaceBy}$2", $text);

        return $text;
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
        if($this->isMixedSnakeCase($string) || $this->isSnakeCase($string)) {
            foreach($separator as $item) {
                $data = $this->convertSnakeToKebab($string, $item);
                $fileNameList[] = $data;
                $fileNameList[] = strtolower($data);
            }
        }
        if($this->isComplexCase($string)) {
            foreach($separator as $item) {
                $data = $this->convertToHyphenCase($string, $item);
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
/*$data = (new ConvertCaseToFileName())->toFileName("isCheckTODORequest");
echo "<pre>" . print_r($data, true) . "</pre>";*/
?>
