<?php
namespace App\DatabaseSchema\Data\Mappers;
?>
<?php
use App\DatabaseSchema\Data\Entities\DatabaseSchema;
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;

class DatabaseSchemaMapper {

    public static function getDataVarList(DatabaseSchema $modelData) {
        return $modelData->getVarList();
    }

    public static function getDomainVarList(DatabaseSchemaModel $modelData) {
        return $modelData->getVarList();
    }

    public static function toData(DatabaseSchema $schema): array {
        // Model data to array data
        $dataVarList = self::getDataVarList($schema);
        $domainVarList = self::getDomainVarList(new DatabaseSchemaModel());
        $dataList = array();
        for($i = 0; $i < count($dataVarList); $i++) {
            $dataList[$dataVarList[$i]] = $schema->{$dataVarList[$i]};
        }
        return $dataList;
    }

    public static function toDomain(DatabaseSchemaModel $schema): array {
        // Model data to array data
        $dataVarList = self::getDataVarList(new DatabaseSchema());
        $domainVarList = self::getDomainVarList($schema);
        $dataList = array();
        for($i = 0; $i < count($dataVarList); $i++) {
            $dataList[$dataVarList[$i]] = $schema->{$domainVarList[$i]};
        }
        return $dataList;
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

    public static function toEntity($schema): DatabaseSchema {
        // Database array or object data to "Data" data
        $model = new DatabaseSchema();
        $dataVarList = self::getDataVarList($model);
        $domainVarList = self::getDomainVarList($schema);
        for($i = 0; $i < count($dataVarList); $i++) {
            $model->{$dataVarList[$i]} = $schema->{$domainVarList[$i]};
        }
        return $model;
    }

    public static function toModel($schema): DatabaseSchemaModel {
        // Database array or object data to model or domain data
        $model = new DatabaseSchemaModel();
        if(is_array($schema)) {
            $dataVarList = self::getDataVarList(new DatabaseSchema());
            $domainVarList = self::getDomainVarList($model);
            for ($i = 0; $i < count($dataVarList); $i++) {
                $model->{$domainVarList[$i]} = $schema[$dataVarList[$i]];
            }
        } else if(is_object($schema)) {
            $dataVarList = self::getDataVarList(new DatabaseSchema());
            $domainVarList = self::getDomainVarList($model);
            for ($i = 0; $i < count($dataVarList); $i++) {
                $model->{$domainVarList[$i]} = $schema->{$dataVarList[$i]};
            }
        }
        return $model;
    }

    public static function toDomainToEntity(DatabaseSchemaModel $schema): DatabaseSchema {
        $model = new DatabaseSchema();
        $dataVarList = self::getDataVarList($model);
        $domainVarList = self::getDomainVarList($schema);
        for($i = 0; $i < count($dataVarList); $i++) {
            /*if (array_key_exists($domainVarList[$i], $dataVarList)) {
                echo "Exists";
            }*/
            if($i < count($domainVarList)) {
                $model->{$dataVarList[$i]} = $schema->{$domainVarList[$i]};
            }
        }
        return $model;
    }

    public static function toEntityToDomain(DatabaseSchema $schema): DatabaseSchemaModel {
        $model = new DatabaseSchemaModel();
        $dataVarList = self::getDataVarList($model);
        $domainVarList = self::getDomainVarList($schema);
        for($i = 0; $i < count($dataVarList); $i++) {
            /*if (array_key_exists($domainVarList[$i], $dataVarList)) {
                echo "Exists";
            }*/
            if($i < count($domainVarList)) {
                $model->{$domainVarList[$i]} = $schema->{$dataVarList[$i]};
            }
        }
        return $model;
    }
}
?>