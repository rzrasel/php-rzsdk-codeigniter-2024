<?php
namespace App\Microservice\Data\Mapper\Language;
?>
<?php
use App\Microservice\Schema\Data\Model\Language\LanguageModel;
use App\Microservice\Schema\Domain\Model\Language\LanguageEntity;
?>
<?php
class LanguageMapper {
    public static function getDataVarList(LanguageEntity $entityData) {
        return $entityData->getVarList();
    }

    public static function getDomainVarList(LanguageModel $modelData) {
        return $modelData->getVarList();
    }

    public static function toData($modelData): LanguageEntity {
        //|Initialize a new LanguageEntity instance|------------------|
        $entityData = new LanguageEntity();
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

        //|RETURN THE POPULATED LanguageEntity INSTANCE|--------------|
        return $entityData;
    }

    public static function toDomain($entityData): LanguageModel {
        //|Initialize a new LanguageModel instance|------------------|
        $modelData = new LanguageModel();
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

        //|RETURN THE POPULATED LanguageModel INSTANCE|--------------|
        return $modelData;
    }
}
?>