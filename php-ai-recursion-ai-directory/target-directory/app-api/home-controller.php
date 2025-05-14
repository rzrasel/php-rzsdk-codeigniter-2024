<?php
use \App\Core\BaseController;
?>
<?php
?>
<?php
class HomeController extends BaseController {

    public function index() {
        echo "Welcome to the HomeController!";
    }

    public function showBy($id) {
        echo "Displaying data for ID: " . $id;
    }
}
?>