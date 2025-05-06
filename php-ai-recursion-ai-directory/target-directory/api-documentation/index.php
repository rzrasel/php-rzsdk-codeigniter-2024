<?php
require_once("include.php");
global $baseUrl;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Detailed API documentation for developers.">
    <meta name="keywords" content="API, documentation, PHP, REST, Swagger-like">
    <title>API Documentation</title>
    <link rel="stylesheet" href="<?= $baseUrl; ?>/assets/css/style.css">
</head>
<body>
<div class="container">
    <div class="left-navigation">
        <?php require_once("utility/left-side-menu.php"); ?>
    </div>
    <div class="content">
    <h1>📚 API Documentation Overview</h1>
    <p>Welcome to the centralized API documentation hub. Browse through various categorized endpoints to understand request formats, expected responses, error handling, and integration notes. Use the side menu to quickly navigate through available APIs and services.</p>
    </div>
</div>
</body>
</html>