<?php
namespace App\Microservice\Presentation\View\Language;
?>
<?php
use App\Microservice\Schema\Domain\Model\Language\LanguageEntity;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Presentation\ViewModel\Language\LanguageViewModel;
?>
<?php
class LanguageView {
    private $viewModel;

    public function __construct(LanguageViewModel $viewModel) {
        $this->viewModel = $viewModel;
    }

    public function createLanguage(LanguageEntity $language): ResponseData {
        $this->viewModel->createLanguage($language);
        return new ResponseData("Language created successfully.", ResponseStatus::SUCCESS);
    }

    public function updateLanguage(LanguageEntity $language): ResponseData {
        $this->viewModel->updateLanguage($language);
        return new ResponseData("Language updated successfully.", ResponseStatus::SUCCESS);
    }

    /*public function deleteLanguage(int $id): ResponseData {
        $this->viewModel->deleteLanguage($id);
        return new ResponseData("Language deleted successfully.", ResponseType::SUCCESS);
    }*/
}
?>