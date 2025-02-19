<?php
namespace App\DatabaseSchema\Helper\Recursion\Traverse;
?>
<?php
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use RzSDK\Log\DebugLog;
?>
<?php
class RecursiveCallbackSingleModelData {
    public function traverseDatabaseSchema(array $databaseSchemas, callable $callback): string {
        $result = "";
        if(!empty($databaseSchemas)) {
            foreach ($databaseSchemas as $schema) {
                $result .= $callback($schema);

                /*// Process nested table data
                foreach($schema->tableDataList as $tableData) {
                    $result .= $this->traverseTableData($tableData, $callback);
                }*/
                if(!empty($schema->tableDataList)) {
                    $result .= $this->traverseTableData($schema->tableDataList, $callback);
                }
            }
        }
        return $result;
    }
    //
    public function traverseTableData(array $tableDataList, callable $callback): string {
        $result = "";
        if(!empty($tableDataList)) {
            foreach($tableDataList as $tableData) {
                //DebugLog::log($tableData);
                $result .= $callback($tableData);

                if(!empty($tableData->columnDataList)) {
                    $result .= $this->traverseColumnData($tableData->columnDataList, $callback, $tableData->tableName);
                }
            }
        }
        return $result;
    }
    //
    public function traverseColumnData(array $columnDataList, callable $callback, $tableName = null): string {
        $result = "";
        if(!empty($columnDataList)) {
            foreach($columnDataList as $columnData) {
                //DebugLog::log($tableData);
                $result .= $callback($columnData, $tableName);
            }
        }
        return $result;
    }
    //
    public function onRecursionTraverse($recursiveData, callable $callback, int $level = 0): string {
        $result = "";

        // Handle arrays
        if(!empty($recursiveData) && is_array($recursiveData)) {
            foreach ($recursiveData as $item) {
                // Apply callback to the current item
                $result .= $callback($item, $level);
                //DebugLog::log($item);

                // Recursively process nested data
                if(is_array($item) || is_object($item)) {
                    $result .= $this->onRecursionTraverse($item, $callback, $level + 1);
                }
            }
        } elseif(!empty($recursiveData)) {
            // Handle single items (non-array)
            //$result .= $callback($recursiveData, $level);
            /*if($level <= 0) {
                $result .= $callback($recursiveData, $level);
            }*/
        }

        return $result;
    }
}
?>
