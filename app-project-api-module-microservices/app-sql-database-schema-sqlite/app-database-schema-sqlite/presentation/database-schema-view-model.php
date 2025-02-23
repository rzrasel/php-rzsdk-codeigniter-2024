<?php
namespace App\DatabaseSchema\Presentation\ViewModels;
?>
<?php
use App\DatabaseSchema\Data\Entities\DatabaseSchema;
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use App\DatabaseSchema\Domain\Repositories\DatabaseSchemaRepositoryInterface;
use RzSDK\Identification\UniqueIntId;
?>
<?php
class DatabaseSchemaViewModel {
    private $repository;

    public function __construct(DatabaseSchemaRepositoryInterface $repository) {
        $this->repository = $repository;
    }
    //
    public function createFromPostData($postData): void {
        $uniqueIntId = new UniqueIntId();
        $tempDatabaseSchema = new DatabaseSchema();
        $databaseSchemaModel = new DatabaseSchemaModel();
        //
        $schemaName = trim($postData[$tempDatabaseSchema->schema_name]);
        $schemaName = preg_replace("/\s+/", "_", $schemaName);
        $schemaName = preg_replace("/-/", "_", $schemaName);
        //
        $databaseSchemaModel->id = $uniqueIntId->getId();
        $databaseSchemaModel->schemaName = $schemaName;
        $databaseSchemaModel->schemaVersion = $postData[$tempDatabaseSchema->schema_version];
        $databaseSchemaModel->tablePrefix = $postData[$tempDatabaseSchema->table_prefix];
        $databaseSchemaModel->databaseComment = $postData[$tempDatabaseSchema->database_comment];
        $databaseSchemaModel->modifiedDate = date('Y-m-d H:i:s');
        $databaseSchemaModel->createdDate = date('Y-m-d H:i:s');
        $this->repository->create($databaseSchemaModel);
    }
    //

    public function getAllDatabaseSchema(): array|bool {
        return $this->repository->getAllData();
    }

    public function getSchema(int $id): ?DatabaseSchemaModel {
        return $this->repository->getById($id);
    }

    public function insertSchema(DatabaseSchemaModel $schema) {
        $this->repository->save($schema);
    }

    public function createSchema(DatabaseSchemaModel $schema): void {
        $this->repository->create($schema);
    }
}
?>