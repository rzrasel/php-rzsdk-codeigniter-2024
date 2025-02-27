<?php
namespace App\DatabaseSchema\Presentation\ViewModels;
?>
<?php
use App\DatabaseSchema\Domain\Repositories\ExtractDatabaseSchemaInterface;
?>
<?php
class ExtractDatabaseSchemaViewModel {
    private $repository;

    public function __construct(ExtractDatabaseSchemaInterface $repository) {
        $this->repository = $repository;
    }
}
?>
