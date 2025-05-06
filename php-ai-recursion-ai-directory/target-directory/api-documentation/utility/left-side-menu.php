<?php
global $baseUrl;
?>
<nav class="left-side-nav">
    <a href="<?= $baseUrl; ?>" class="left-nav-link">🏠 Home</a>
    <a href="<?= "$baseUrl/pages/api-language.php"; ?>" class="left-nav-link">🌐 Language API</a>
    <a href="<?= "$baseUrl/pages/api-user-email.php"; ?>" class="left-nav-link">📧 User E-mail API</a>
    <a href="<?= "$baseUrl/pages/api-user-password.php"; ?>" class="left-nav-link">🔑 User Password API</a>
    <!-- Add more links as needed -->
</nav>