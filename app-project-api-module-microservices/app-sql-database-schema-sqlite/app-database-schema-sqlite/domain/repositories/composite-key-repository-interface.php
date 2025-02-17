<?php
namespace App\DatabaseSchema\Domain\Repositories;
?>
<?php
use App\DatabaseSchema\Domain\Models\CompositeKeyModel;
?>
<?php
interface CompositeKeyRepositoryInterface {
    public function create(CompositeKeyModel $compositeKey): void;
    public function save(CompositeKeyModel $compositeKey): void;
    public function delete(int $id): void;
}
?>