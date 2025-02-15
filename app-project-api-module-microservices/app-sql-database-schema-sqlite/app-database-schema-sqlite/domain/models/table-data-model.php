<?php
namespace App\DatabaseSchema\Domain\Models;
?>
<?php
class TableDataModel {
    public int $schemaId;
    public int $id;
    public string $tableName;
    public ?string $columnPrefix;
    public ?string $tableComment;
    public string $modifiedDate;
    public string $createdDate;
    public function __construct(
        int $schemaId = 0,
        int $id = 0,
        string $tableName = "",
        ?string $tableComment = null,
        ?string $columnPrefix = null,
        ?string $modifiedDate = null,
        ?string $createdDate = null
    ) {
        $this->schemaId = $schemaId;
        $this->id = $id;
        $this->tableName = $tableName;
        $this->tableComment = $tableComment;
        $this->columnPrefix = $columnPrefix;
        $this->modifiedDate = $modifiedDate ?? date('Y-m-d H:i:s');
        $this->createdDate = $createdDate ?? date('Y-m-d H:i:s');
    }
}
?>