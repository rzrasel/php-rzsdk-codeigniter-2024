<?php
namespace App\Microservice\Data\Mapper\User\Email;
?>
<?php
use App\Microservice\Schema\Domain\Model\User\Email\UserEmailEntity;
use App\Microservice\Schema\Data\Model\User\Email\UserEmailRequestModel;
use App\Microservice\Schema\Data\Model\User\Email\UserEmailResponseDto;
?>
<?php
class UserEmailMapper {
    public static function getDataVarList(UserEmailRequestModel $requestDataModel) {
        return $requestDataModel->getVarList();
    }

    public static function getDomainVarList(UserEmailEntity $modelData) {
        return $modelData->getVarList();
    }

    public static function mapRequestToEntity(UserEmailRequestModel $requestDataModel): UserEmailEntity {
        $entityData = new UserEmailEntity();
        $dataVarList = $requestDataModel->getVarList();
        $domainVarList = $entityData->getVarList();
        array_map(function ($key) use ($requestDataModel, $domainVarList, $entityData) {
            if (in_array($key, $domainVarList)) {
                $entityData->{$key} = $requestDataModel->{$key};
            }
        }, $dataVarList);
        return $entityData;
    }

    public static function mapEntityToResponseDto(UserEmailEntity $userEmailEntity): UserEmailResponseDto {
        $userEmailResponseDto = new UserEmailResponseDto();
        $dataVarList = $userEmailResponseDto->getVarList();
        $domainVarList = $userEmailEntity->getVarList();
        foreach ($domainVarList as $key) {
            if (in_array($key, $dataVarList)) {
                $userEmailResponseDto->{$key} = $userEmailEntity->{$key};
            }
        }
        $userEmailResponseDto->user_email = $userEmailEntity->email;
        $userEmailResponseDto->email_provider = $userEmailEntity->provider;
        return $userEmailResponseDto;
    }

    /*public static function mapEntityToParams(UserEmailModel $email): array {
        return [
            ':user_id' => $email->user_id,
            ':id' => $email->id,
            ':email' => $email->email,
            ':provider' => $email->provider,
            ':is_primary' => $email->is_primary,
            ':verification_code' => $email->verification_code,
            ':last_verification_sent_at' => $email->last_verification_sent_at,
            ':verification_code_expiry' => $email->verification_code_expiry,
            ':verification_status' => $email->verification_status,
            ':status' => $email->status,
            ':created_date' => $email->created_date,
            ':modified_date' => $email->modified_date,
            ':created_by' => $email->created_by,
            ':modified_by' => $email->modified_by
        ];
    }*/
}