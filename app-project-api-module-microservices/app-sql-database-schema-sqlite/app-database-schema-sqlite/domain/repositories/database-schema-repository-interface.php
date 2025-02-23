<?php
namespace App\DatabaseSchema\Domain\Repositories;
?>
<?php
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
?>
<?php
interface DatabaseSchemaRepositoryInterface {
    public function create(DatabaseSchemaModel $schema): void;
    //
    public function getAllData(): array|bool;
    public function getById(int $id): ?DatabaseSchemaModel;
    public function save(DatabaseSchemaModel $schema): void;
    public function delete(int $id): void;
}
?>