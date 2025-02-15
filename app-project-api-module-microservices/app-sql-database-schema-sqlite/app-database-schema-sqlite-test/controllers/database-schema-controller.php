<?php
namespace App\Controllers;
?>
<?php
use App\Domain\Models\DatabaseSchemaModel;
use App\Data\Repositories\DatabaseSchemaRepositoryImpl;
?>
<?php
class DatabaseSchemaController {
    private DatabaseSchemaRepositoryImpl $repository;

    public function __construct() {
        $this->repository = new DatabaseSchemaRepositoryImpl();
    }

    public function createSchema(): void {
        $schema = new DatabaseSchemaModel(time(), "MySchema", "1.0", "tbl_", date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
        $this->repository->create($schema);
        echo "Schema Created!";
    }
}
?>