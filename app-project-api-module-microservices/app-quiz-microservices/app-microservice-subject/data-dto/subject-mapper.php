<?php
namespace App\Microservice\Data\Mapper\Subject;
?>
<?php
use App\Microservice\Schema\Domain\Model\Subject\SubjectEntity;
use App\Microservice\Schema\Data\Model\Subject\SubjectModel;
?>
<?php
class SubjectMapper {
    public static function getDataVarList(SubjectEntity $entityData) {
        return $entityData->getVarList();
    }

    public static function getDomainVarList(SubjectModel $modelData) {
        return $modelData->getVarList();
    }

    public static function toData($modelData): SubjectEntity {
        //|Initialize a new SubjectEntity instance|------------------|
        $entityData = new SubjectEntity();
        //|Get entity and domain variable names|---------------------|
        $dataVarList = self::getDataVarList($entityData);
        $domainVarList = self::getDomainVarList($modelData);

        //|-------|MODEL DATA ARRAY AND OBJECT MAP TO ENTITY|--------|
        if(is_array($modelData)) {
            array_map(function($key) use($entityData, $modelData) {
                if (array_key_exists($key, $modelData)) {
                    $entityData->{$key} = $modelData[$key];
                }
            }, $dataVarList);
        } else if(is_object($modelData)) {
            array_map(function($dataVar, $domainVar) use($entityData, $modelData) {
                if(property_exists($modelData, $domainVar)) {
                    $entityData->{$dataVar} = $modelData->{$domainVar};
                }
            }, $dataVarList, $domainVarList);
        }

        //|RETURN THE POPULATED SubjectEntity INSTANCE|--------------|
        return $entityData;
    }

    public static function toDomain($entityData): SubjectModel {
        //|Initialize a new SubjectModel instance|------------------|
        $modelData = new SubjectModel();
        //|Get entity and domain variable names|---------------------|
        $dataVarList = self::getDataVarList($entityData);
        $domainVarList = self::getDomainVarList($modelData);

        //|-------|ENTITY DATA ARRAY AND OBJECT MAP TO MODEL|--------|
        if(is_array($entityData)) {
            array_map(function($entityKey, $modelKey) use($modelData, $entityData) {
                if(array_key_exists($entityKey, $entityData)) {
                    $modelData->{$modelKey} = $entityData[$entityKey];
                }
            }, $dataVarList, $domainVarList);
        } else if(is_object($entityData)) {
            array_map(function($entityKey, $modelKey) use($modelData, $entityData) {
                if(property_exists($entityData, $entityKey)) {
                    $modelData->{$modelKey} = $entityData->{$entityKey};
                }
            }, $dataVarList, $domainVarList);
        }

        //|RETURN THE POPULATED SubjectModel INSTANCE|--------------|
        return $modelData;
    }
}
?>