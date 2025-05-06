<?php
namespace App\Microservice\Data\Mapper\User\Password;
?>
<?php
use App\Microservice\Schema\Domain\Model\User\Email\UserEmailEntity;
use App\Microservice\Schema\Data\Model\User\Email\UserEmailRequestModel;
use App\Microservice\Schema\Data\Model\User\Email\UserEmailResponseDto;
use RzSDK\Database\SqliteFetchType;
use App\Microservice\Schema\Domain\Model\User\Password\UserPasswordEntity;
use App\Microservice\Schema\Data\Model\User\Password\UserPasswordModel;
use App\Microservice\Schema\Data\Model\User\Password\UserPasswordResponseModel;
?>
<?php
class UserPasswordMapper {
    public static function getDataVarList(UserEmailRequestModel $requestDataModel) {
        return $requestDataModel->getVarList();
    }

    public static function getDomainVarList(UserEmailEntity $modelData) {
        return $modelData->getVarList();
    }

    public static function mapModelToEntity(UserPasswordModel $dataModel): UserPasswordEntity {
        $entityData = new UserPasswordEntity();
        $dataVarList = $dataModel->getVarList();
        $domainVarList = $entityData->getVarList();
        foreach ($domainVarList as $key) {
            if (in_array($key, $dataVarList)) {
                $entityData->{$key} = $dataModel->{$key};
            }
        }
        return $entityData;
    }

    public static function mapModelToResponseModel(UserPasswordModel $dataModel): UserPasswordResponseModel {
        $responseModel = new UserPasswordResponseModel();
        $dataVarList = $responseModel->getVarList();
        $domainVarList = $dataModel->getVarList();
        foreach ($dataVarList as $key) {
            if (in_array($key, $domainVarList)) {
                $responseModel->{$key} = $dataModel->{$key};
            }
        }
        return $responseModel;
    }

    public static function mapEntityToResponseModel(UserPasswordEntity $domainModel): UserPasswordResponseModel {
        $responseModel = new UserPasswordResponseModel();
        $dataVarList = $responseModel->getVarList();
        $domainVarList = $domainModel->getVarList();
        foreach ($dataVarList as $key) {
            if (in_array($key, $domainVarList)) {
                $responseModel->{$key} = $domainModel->{$key};
            }
        }
        return $responseModel;
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
        $mappedKey = UserEmailRequestModel::mapKeyToUserInput();
        foreach ($mappedKey as $key => $value) {
            if (in_array($key, $domainVarList)) {
                $userEmailResponseDto->{$value} = $userEmailEntity->{$key};
            }
        }
        return $userEmailResponseDto;
    }

    public static function mapDbToEntity($dbData): array {
        //echo gettype($dbData);
        //print_r($dbData);
        $retVal = array();
        $entityData = new UserEmailEntity();
        $dataVarList = $entityData->getVarList($entityData);
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
}