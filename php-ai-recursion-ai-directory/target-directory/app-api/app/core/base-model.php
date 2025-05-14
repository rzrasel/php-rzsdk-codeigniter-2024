<?php
namespace App\Core;
?>
<?php
use App\Core\Database;
?>
<?php
class BaseModel {
    protected $db;

    public function __construct() {
        $this->db = Database::connect();
    }
}