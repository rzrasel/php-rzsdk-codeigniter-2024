<?php
namespace Data\Mappers;
?>
<?php
use Data\Models\LanguageModel;
use Domain\Models\LanguageEntity;
?>
<?php
class LanguageMapper {
    public static function toEntity(LanguageModel $model): LanguageEntity {
        $entity = new LanguageEntity();
        $entity->setId($model->id);
        $entity->setIsoCode2($model->iso_code_2);
        $entity->setIsoCode3($model->iso_code_3);
        $entity->setName($model->name);
        return $entity;
    }

    public static function toModel(LanguageEntity $entity): LanguageModel {
        $model = new LanguageModel();
        $model->id = $entity->getId();
        $model->iso_code_2 = $entity->getIsoCode2();
        $model->iso_code_3 = $entity->getIsoCode3();
        $model->name = $entity->getName();
        return $model;
    }
}
?>