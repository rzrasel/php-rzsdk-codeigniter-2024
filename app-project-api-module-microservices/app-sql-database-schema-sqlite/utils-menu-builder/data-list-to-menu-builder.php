<?php
namespace App\Utils\Menu\Builder;
?>
<?php
class DataListToMenuBuilder {
    public function build(array $dataList, callable $callback, $path = '') {
        $data = "";
        foreach ($dataList as $key => $item) {
            $fullPath = !empty($path) ? "{$path}/{$key}" : $key;
            if(is_array($item)) {
                // Parent item
                $data .= $callback($key, $item, $fullPath, true);
                // Recursive call for child items
                $data .= $this->build($item, $callback, $fullPath);
                // Closing tag for parent item
                $data .= $callback($key, $item, $fullPath, false);
            } else {
                // Normal menu item
                $data .= $callback($key, $item, $fullPath, false);
            }
        }
        return $data;
    }
}
?>