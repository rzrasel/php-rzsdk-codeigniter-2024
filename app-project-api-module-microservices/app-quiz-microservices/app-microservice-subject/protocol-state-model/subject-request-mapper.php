<?php
namespace App\Microservice\Data\Mapper\Subjec;
?>
<?php
use App\Microservice\Schema\Data\Model\Subject\SubjectModel;
use App\Microservice\Protocol\State\Model\Request\Subject\SubjectRequestData;
?>
<?php
class SubjectRequestMapper {
    public static function toModel(SubjectRequestData $data): SubjectModel {
        return SubjectModel::set(
            $data->language_id,
            null,
            $data->subject_name,
            $data->description,
            $data->subject_code,
            $data->subject_identity,
            $data->slug,
        );
    }
}
?>