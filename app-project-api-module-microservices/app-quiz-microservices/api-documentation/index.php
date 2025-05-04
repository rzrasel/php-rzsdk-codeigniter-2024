<?php
require_once("include.php");
global $baseUrl;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation</title>
    <link rel="stylesheet" href="<?= $baseUrl; ?>/assets/css/style.css">
</head>
<body>
<div class="container">
    <div class="left-navigation">
        <?php require_once("utility/left-side-menu.php"); ?>
    </div>
    <div class="content">
    <h1>📚 API Documentation Index</h1>
    <p>Select a documentation page below:</p>
    </div>
</div>
</body>
</html>