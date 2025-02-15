<?php
// app/Data/Mappers/DatabaseSchemaMapper.php
namespace App\Data\Mappers;
?>
<?php
use App\Data\Entities\DatabaseSchema;
use App\Domain\Models\DatabaseSchemaModel;

class DatabaseSchemaMapper {
    /*public static function toDomain(array $data): DatabaseSchema {
        $schema = new DatabaseSchema();
        $schema->id = $data['id'];
        $schema->schema_name = $data['schema_name'];
        $schema->schema_version = $data['schema_version'];
        $schema->table_prefix = $data['table_prefix'];
        $schema->modified_date = $data['modified_date'];
        $schema->created_date = $data['created_date'];
        return $schema;
    }*/

    public static function toDataArray(DatabaseSchema $schema): array {
        return [
            'id' => $schema->id,
            'schema_name' => $schema->schema_name,
            'schema_version' => $schema->schema_version,
            'table_prefix' => $schema->table_prefix,
            'modified_date' => $schema->modified_date,
            'created_date' => $schema->created_date,
        ];
    }

    public static function toDomainDataArray(DatabaseSchemaModel $schema): array {
        return [
            'id' => $schema->id,
            'schema_name' => $schema->schemaName,
            'schema_version' => $schema->schemaVersion,
            'table_prefix' => $schema->tablePrefix,
            'modified_date' => $schema->modifiedDate,
            'created_date' => $schema->createdDate,
        ];
    }

    public static function toDomainModel(DatabaseSchema $dataModel) {
        $domainModel = new DatabaseSchemaModel();
        $domainModel->id = $dataModel->id;
        $domainModel->schemaName = $dataModel->schema_name;
        $domainModel->schemaVersion = $dataModel->schema_version;
        $domainModel->tablePrefix = $dataModel->table_prefix;
        $domainModel->modifiedDate = $dataModel->modified_date;
        $domainModel->createdDate = $dataModel->created_date;
        return $domainModel;
    }

    public static function toDataModel(DatabaseSchemaModel $domainModel) {
        $dataModel = new DatabaseSchema();
        $dataModel->id = $domainModel->id;
        $dataModel->schema_name = $domainModel->schemaName;
        $dataModel->schema_version = $domainModel->schemaVersion;
        $dataModel->table_prefix = $domainModel->tablePrefix;
        $dataModel->modified_date = $domainModel->modifiedDate;
        $dataModel->created_date = $domainModel->createdDate;
        return $dataModel;
    }

    public static function toData($schema): DatabaseSchemaModel {
        $model = new DatabaseSchemaModel(
            $schema->id,
            $schema->schema_name,
            $schema->schema_version,
            $schema->table_prefix,
            $schema->modified_date,
            $schema->created_date
        );
        /*$model->id = $schema->id;
        $model->schemaName = $schema->schema_name;
        $model->schemaVersion = $schema->schema_version;
        $model->tablePrefix = $schema->table_prefix;
        $model->modifiedDate = $schema->modified_date;
        $model->createdDate = $schema->created_date;*/
        return $model;
    }
}
?>