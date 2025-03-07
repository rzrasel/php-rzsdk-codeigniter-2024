<?php
namespace RzSDK\Autoloader;
?>
<?php
class FindArrayKeyByValue {
    public function findByValue($array, $value) {
        $matchingKeys = [];

        foreach ($array as $key => $val) {
            if (strpos($val, $value) !== false) { // Check if $value exists in $val
                $matchingKeys[] = $key; // Store matching key
            }
        }

        return !empty($matchingKeys) ? $matchingKeys : false; // Return array or false if no match
    }

    public function findByValueExactMatch($array, $value) {
        $matchingKeys = [];

        foreach ($array as $key => $val) {
            if ($val === $value) { // Check for exact match
                $matchingKeys[] = $key; // Store matching key
            }
        }

        return !empty($matchingKeys) ? $matchingKeys : false; // Return array of keys or false
    }
}
?>
<?php
/*// Sample Data
$data = array(
    "key-1" => "value-1-part-1-data",
    "key-2" => "value-2-part-2-data",
    "key-3" => "value-3-part-3-data",
    "key-4" => "value-4-part-4-data",
    "key-5" => "value-5-part-5-data",
    "key-6" => "value-6-part-5-data",
    "key-7" => "value-7-part-5-data",
);

// Usage Example
$find = new FindArrayKeyByValue();
echo "<br />";
print_r($find->findByValue($data, "part-1")); // ["key-1"]
echo "<br />";
print_r($find->findByValue($data, "value-3")); // ["key-3"]
echo "<br />";
print_r($find->findByValue($data, "5-data")); // ["key-5", "key-6"]
echo "<br />";*/
?>
