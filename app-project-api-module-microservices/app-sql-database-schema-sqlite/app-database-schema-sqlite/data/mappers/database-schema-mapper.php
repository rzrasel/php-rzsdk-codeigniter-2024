<?php
namespace App\DatabaseSchema\Data\Mappers;
?>
<?php
use App\DatabaseSchema\Data\Entities\DatabaseSchema;
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;

class DatabaseSchemaMapper {

    public static function toData(DatabaseSchema $schema): array {
        return [
            'id' => $schema->id,
            'schema_name' => $schema->schema_name,
            'schema_version' => $schema->schema_version,
            'table_prefix' => $schema->table_prefix,
            'database_comment' => $schema->database_comment,
            'modified_date' => $schema->modified_date,
            'created_date' => $schema->created_date,
        ];
    }

    public static function toDomain(DatabaseSchemaModel $schema): array {
        return [
            'id' => $schema->id,
            'schema_name' => $schema->schemaName,
            'schema_version' => $schema->schemaVersion,
            'table_prefix' => $schema->tablePrefix,
            'database_comment' => $schema->databaseComment,
            'modified_date' => $schema->modifiedDate,
            'created_date' => $schema->createdDate,
        ];
    }

    public static function toDataParams(DatabaseSchema $schema): array {
        $params = [];
        $data = self::toData($schema);
        foreach($data as $key => $value) {
            $params[":$key"] = $value;
        }
        return $params;
    }

    public static function toDomainParams(DatabaseSchemaModel $schema): array {
        $params = [];
        $data = self::toDomain($schema);
        foreach($data as $key => $value) {
            $params[":$key"] = $value;
        }
        return $params;
    }

    public function toEntity($schema): DatabaseSchema {
        $model = new DatabaseSchema();
        $model->id = $schema->id;
        $model->schema_name = $schema->schema_name;
        $model->schema_version = $schema->schema_version;
        $model->table_prefix = $schema->table_prefix;
        $model->database_comment = $schema->database_comment;
        $model->modified_date = $schema->modified_date;
        $model->created_date = $schema->created_date;
        return $model;
    }

    public function toModel($schema): DatabaseSchemaModel {
        $model = new DatabaseSchemaModel();
        $model->id = $schema->id;
        $model->schemaName = $schema->schema_name;
        $model->schemaVersion = $schema->schema_version;
        $model->tablePrefix = $schema->table_prefix;
        $model->databaseComment = $schema->database_comment;
        $model->modifiedDate = $schema->modified_date;
        $model->createdDate = $schema->created_date;
        return $model;
    }
}
?>