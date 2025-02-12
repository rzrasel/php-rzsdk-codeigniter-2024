<?php
class FindArrayKeyByValue {
    public function findByValue($array, $value) {
        foreach ($array as $key => $val) {
            if (strpos($val, $value) !== false) { // Check if $value exists in $val
                return $key; // Return the matching key
            }
        }
        return false; // Return false if no match is found
    }
}
?>
<?php
// Sample Data
$data = array(
    "key-1" => "value-1-part-1-data",
    "key-2" => "value-2-part-2-data",
    "key-3" => "value-3-part-3-data",
    "key-4" => "value-4-part-4-data",
    "key-5" => "value-5-part-5-data"
);

// Usage Example
$find = new FindArrayKeyByValue();
echo "<br />";
echo $find->findByValue($data, "part-1");
echo "<br />";
echo $find->findByValue($data, "value-3");
echo "<br />";
echo $find->findByValue($data, "5-data");
echo "<br />";
?>
