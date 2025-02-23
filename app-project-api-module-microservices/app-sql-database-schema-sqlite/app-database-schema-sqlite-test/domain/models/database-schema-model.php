<?php
// app/Data/Models/DatabaseSchemaModel.php
namespace App\Domain\Models;
?>
<?php
class DatabaseSchemaModel {
    public $id;
    public $schemaName;
    public $schemaVersion;
    public $tablePrefix;
    public $modifiedDate;
    public $createdDate;
    public function __construct(int $id, string $schemaName, ?string $schemaVersion, ?string $tablePrefix, string $modifiedDate, string $createdDate) {
        $this->id = $id;
        $this->schemaName = $schemaName;
        $this->schemaVersion = $schemaVersion;
        $this->tablePrefix = $tablePrefix;
        $this->modifiedDate = $modifiedDate;
        $this->createdDate = $createdDate;
    }
}
?>