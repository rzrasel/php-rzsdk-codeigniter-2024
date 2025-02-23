<?php
require_once("include.php");
?>
<?php
use App\Utils\Menu\Builder\BuildHtmlMenu;
use App\Utils\Menu\Builder\HtmlMenuDataList;
?>
<?php
global $workingBaseUrl;
?>
<?php
$dataList = HtmlMenuDataList::sqlDatabaseDataList();
?>
<?php
$buildHtmlMenu = new BuildHtmlMenu();
$sideBarMenu = $buildHtmlMenu->buildTopbarMenu($dataList, "{$workingBaseUrl}/");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sql Database Schema SQLite</title>
    <link rel="stylesheet" type="text/css" href="<?= $workingBaseUrl; ?>/css/style.css">
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let messages = document.querySelectorAll(".data-entry-message div");
            if (messages.length > 0) {
                messages[0].classList.add("active"); // Show first message
            }
        });
    </script>
</head>
<body>
<table class="main-body-container">
    <tr>
        <td class="main-left-sidebar-container"><?= $sideBarMenu; ?></td>
        <td class="main-body-content-container">
            <?php
            require_once("view-parts/column-data-entry-part.php");
            ?>
        </td>
    </tr>
</table>
</body>
</html>