<?php
// app/Presentation/ViewModels/DatabaseSchemaViewModel.php
namespace App\Presentation\ViewModels;
?>
<?php
use App\Data\Entities\DatabaseSchema;
use App\Domain\Models\DatabaseSchemaModel;
use App\Domain\Repositories\DatabaseSchemaRepositoryInterface;
?>
<?php
class DatabaseSchemaViewModel {
    private $repository;

    public function __construct(DatabaseSchemaRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function getSchema(int $id): ?DatabaseSchemaModel {
        return $this->repository->findById($id);
    }
}
?>