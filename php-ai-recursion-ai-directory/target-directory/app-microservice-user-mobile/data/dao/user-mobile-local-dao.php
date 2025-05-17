<?php
namespace App\Microservice\Data\Data\Access\Object\User\Mobile;
?>
<?php
use App\Microservice\Schema\Data\Model\Entity\User\Mobile\UserMobileEntity;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
?>
<?php
class UserMobileLocalDAO {
    private array $db = [];

    public function insert(UserMobileEntity $user): ResponseData {
        $this->db[$user->id] = $user;
        return true;
    }

    public function findById(string $id): ResponseData {
        return $this->db[$id];
    }
}
?>