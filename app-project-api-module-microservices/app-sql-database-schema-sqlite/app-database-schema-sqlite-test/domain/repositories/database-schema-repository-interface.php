<?php
namespace App\Domain\Repositories;
?>
<?php
use App\Data\Entities\DatabaseSchema;
use App\Domain\Models\DatabaseSchemaModel;
?>
<?php
interface DatabaseSchemaRepositoryInterface {
    public function findById(int $id): ?DatabaseSchemaModel;
    //public function create(DatabaseSchemaModel $schema): void;
    public function save(DatabaseSchemaModel $schema): void;
    public function delete(int $id): void;
}
?>