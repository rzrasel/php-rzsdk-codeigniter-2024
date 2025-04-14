<?php
namespace App\Microservice\Domain\Repository\Subject;
?>
<?php
use App\Microservice\Schema\Domain\Model\Subject\SubjectEntity;
use App\Microservice\Core\Utils\Data\Inner\Data\Bus\InnerDataBus;
?>
<?php
interface SubjectRepository {
    public function createLanguage(SubjectEntity $subject): InnerDataBus;
    public function updateLanguage(SubjectEntity $subject): void;
}
?>