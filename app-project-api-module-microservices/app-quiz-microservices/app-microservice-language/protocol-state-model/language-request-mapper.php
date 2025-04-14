<?php
namespace App\Microservice\Data\Mapper\Language;
?>
<?php
use App\Microservice\Schema\Data\Model\Language\LanguageModel;
use App\Microservice\Protocol\State\Model\Request\Language\LanguageRequestData;
?>
<?php
class LanguageRequestMapper {
    public static function toModel(LanguageRequestData $data): LanguageModel {
        return LanguageModel::set(
            null,
            $data->language_name,
            $data->iso_code_2,
            $data->iso_code_3,
            $data->slug,
        );
    }
}
?>
