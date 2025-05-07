<?php
namespace App\Microservice\Data\Mapper\User\Email;
?>
<?php
use App\Microservice\Schema\Domain\Model\User\Email\UserEmailEntity;
use App\Microservice\Schema\Data\Model\User\Email\UserEmailModel;
use App\Microservice\Schema\Data\Model\User\Email\UserEmailResponseDto;
use RzSDK\Database\SqliteFetchType;
?>
<?php
class UserEmailMapper {
    public static function getDataVarList(UserEmailModel $requestDataModel) {
        return $requestDataModel->getVarList();
    }

    public static function getDomainVarList(UserEmailEntity $modelData) {
        return $modelData->getVarList();
    }

    public static function mapModelToEntity(UserEmailModel $dataModel): UserEmailEntity {
        $entityData = new UserEmailEntity();
        $dataVarList = $dataModel->getVarList();
        $domainVarList = $entityData->getVarList();
        array_map(function ($key) use ($dataModel, $domainVarList, $entityData) {
            if (in_array($key, $domainVarList)) {
                $entityData->{$key} = $dataModel->{$key};
            }
        }, $dataVarList);
        return $entityData;
    }

    public static function mapEntityToResponseDto(mixed $domainModel): mixed {
        if (is_array($domainModel)) {
            $retDataList = array();
            $userEmailResponseDto = new UserEmailResponseDto();
            $userEmailEntity = new UserEmailEntity();
            $dataVarList = $userEmailResponseDto->getVarList();
            $domainVarList = $userEmailEntity->getVarList();
            foreach($domainModel as $item) {
                $responseModel = new UserEmailResponseDto();
                foreach ($dataVarList as $key) {
                    if (in_array($key, $domainVarList)) {
                        $responseModel->{$key} = $item->{$key};
                    }
                }
                $responseModel->user_email = $item->email;
                $responseModel->email_provider = $item->provider;
                $retDataList[] = $responseModel;
            }
            return $retDataList;
        }
        $userEmailResponseDto = new UserEmailResponseDto();
        $dataVarList = $userEmailResponseDto->getVarList();
        $domainVarList = $domainModel->getVarList();
        foreach ($domainVarList as $key) {
            if (in_array($key, $dataVarList)) {
                $userEmailResponseDto->{$key} = $domainModel->{$key};
            }
        }
        $userEmailResponseDto->user_email = $domainModel->email;
        $userEmailResponseDto->email_provider = $domainModel->provider;
        return $userEmailResponseDto;
    }

    public static function mapDbToEntity($dbData): array {
        //echo gettype($dbData);
        //print_r($dbData);
        $retVal = array();
        $entityData = new UserEmailEntity();
        $dataVarList = $entityData->getVarList();
        if(is_object($dbData)) {
            while ($row = $dbData->fetch(SqliteFetchType::FETCH_OBJ->value)) {
                $entityData = new UserEmailEntity();
                foreach ($dataVarList as $key) {
                    $entityData->{$key} = $row->{$key};
                }
                $retVal[] = $entityData;
            }
            /*foreach ($dbData as $item) {
                //print_r($item);
                $entityData = new UserEmailEntity();
                foreach ($dataVarList as $key) {
                    $entityData->{$key} = $item[$key];
                }
                $retVal[] = $entityData;
            }*/
        } else if(is_array($dbData)) {
            foreach ($dbData as $item) {
                print_r($item);
                $entityData = new UserEmailEntity();
                /*foreach ($dataVarList as $key) {
                    $entityData->{$key} = $item[$key];
                }*/
                $retVal[] = $entityData;
            }
        }
        return $retVal;
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
?>