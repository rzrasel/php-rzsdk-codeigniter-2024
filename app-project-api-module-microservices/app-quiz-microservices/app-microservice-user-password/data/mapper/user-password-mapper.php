<?php
namespace App\Microservice\Data\Mapper\User\Password;
?>
<?php
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

    public static function getDomainVarList(UserPasswordEntity $modelData) {
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

    public static function mapEntityToResponseModel(mixed $domainModel): mixed {
        if (is_array($domainModel)) {
            $retDataList = array();
            $responseModel = new UserPasswordResponseModel();
            $userPasswordEntity = new UserPasswordEntity();
            $dataVarList = $responseModel->getVarList();
            $domainVarList = $userPasswordEntity->getVarList();
            foreach($domainModel as $item) {
                $responseModel = new UserPasswordResponseModel();
                foreach ($dataVarList as $key) {
                    if (in_array($key, $domainVarList)) {
                        $responseModel->{$key} = $item->{$key};
                    }
                }
                $retDataList[] = $responseModel;
            }
            return $retDataList;
        }
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
    public static function mapEntityToResponseModelV1(UserPasswordEntity $domainModel): UserPasswordResponseModel {
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

    public static function mapDbToEntity($dbData): array {
        $retVal = array();
        $entityData = new UserPasswordEntity();
        $dataVarList = $entityData->getVarList();
        if(is_object($dbData)) {
            while ($row = $dbData->fetch(SqliteFetchType::FETCH_OBJ->value)) {
                $entityData = new UserPasswordEntity();
                foreach ($dataVarList as $key) {
                    $entityData->{$key} = $row->{$key};
                }
                $retVal[] = $entityData;
            }
        }
        return $retVal;
    }
}