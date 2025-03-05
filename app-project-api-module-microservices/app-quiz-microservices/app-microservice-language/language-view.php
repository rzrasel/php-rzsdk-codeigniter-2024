<?php
namespace App\Microservice\Presentation\View\Language;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseType;
use App\Microservice\Presentation\ViewModel\Language\LanguageViewModel;
?>
<?php
class LanguageView {
    private $viewModel;

    public function __construct(LanguageViewModel $viewModel) {
        $this->viewModel = $viewModel;
    }

    public function renderAllLanguages(): ResponseData {
        $languages = $this->viewModel->getAllLanguages();
        return new ResponseData("Languages retrieved successfully.", ResponseType::SUCCESS, $languages);
    }
}
?>