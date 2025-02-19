<?php
namespace App\DatabaseSchema\Helper\Recursion\Traverse;
?>
<?php
class RecursiveCallbackSingleModelData {
    public function traverseDatabaseSchema(array $databaseSchemas, callable $callback): string {
        $result = "";
        foreach($databaseSchemas as $schema) {
            $result .= $callback($schema);
        }
        return $result;
    }
}
?>
