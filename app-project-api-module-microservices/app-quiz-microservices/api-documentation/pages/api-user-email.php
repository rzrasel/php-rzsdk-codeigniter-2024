<?php
require_once("include.php");
global $baseUrl;
$baseUrl = dirname($baseUrl);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User E-mail API Documentation</title>
    <link rel="stylesheet" href="<?= $baseUrl; ?>/assets/css/style.css">
</head>
<body>
<div class="container" id="top">
    <div class="left-navigation">
        <?php require_once("../utility/left-side-menu.php"); ?>
    </div>
    <div class="content">
        <h1>📧 User E-mail API Documentation</h1>

        <div class="anchor-menu">
            <a href="#endpoint">Endpoint</a>
            <a href="#overview">Overview</a>
            <a href="#request-body">Request</a>
            <a href="#success-response">Success</a>
            <a href="#error-405">Error 405</a>
            <a href="#error-409">Error 409</a>
            <a href="#error-codes">Error Codes</a>
            <a href="#notes">Notes</a>
        </div>

        <section id="endpoint" class="api-section">
            <h2>API Endpoint <span class="method-tag">POST</span></h2>
            <code><?= dirname($baseUrl) . "/app-microservice-user-email/"; ?></code>
        </section>

        <section id="overview" class="api-section">
            <h2>Overview</h2>
            <p>This endpoint registers or updates a user's email in the system.</p>
            <ul>
                <li><strong>Method:</strong> POST</li>
                <li><strong>Authorization:</strong> None</li>
                <li><strong>Content-Type:</strong> application/json</li>
            </ul>
        </section>

        <section id="request-body" class="api-section">
            <h2>Request Body</h2>
            <pre>{
  "user_id": "989898",
  "user_email": "email@rzrasel.com",
  "email_provider": "user",
  "is_primary": "false",
  "verification_code": "",
  "action_type": "insert"
}</pre>
        </section>

        <section id="success-response" class="api-section">
            <h2>Success Response <span class="status-code">200 OK</span></h2>
            <pre>{
  "message": "'email@rzrasel.com' email already exists.",
  "status": "error",
  "data": {
    "user_id": "989898",
    "id": "174653866244413319",
    "user_email": "email@rzrasel.com",
    "email_provider": "user",
    "is_primary": "false",
    "last_verification_sent_at": null,
    "verification_code_expiry": null,
    "verification_status": "pending",
    "status": "active"
  },
  "status_code": 200
}</pre>
        </section>

        <section id="error-405" class="api-section">
            <h2>Error Response: Method Not Allowed <span class="status-code">405 ERROR</span></h2>
            <pre>{
  "message": "Only POST method is allowed",
  "status": "error",
  "data": null,
  "status_code": 405
}</pre>
        </section>

        <section id="error-409" class="api-section">
            <h2>Error Response: Duplicate Email <span class="status-code">409 ERROR</span></h2>
            <pre>{
  "message": "'email@rzrasel.com' email already exists.",
  "status": "error",
  "data": {
    "user_id": "989898",
    "id": "174653870819244893",
    "user_email": "email@rzrasel.com",
    "email_provider": "user",
    "is_primary": "false",
    "last_verification_sent_at": null,
    "verification_code_expiry": null,
    "verification_status": "pending",
    "status": "active"
  },
  "status_code": 409
}</pre>
        </section>

        <section id="error-codes" class="api-section">
            <h2>Error Codes</h2>
            <table>
                <thead>
                <tr>
                    <th>Status Code</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>400</td>
                    <td>Bad Request – Invalid or missing parameters</td>
                </tr>
                <tr>
                    <td>405</td>
                    <td>Method Not Allowed – Only POST is accepted</td>
                </tr>
                <tr>
                    <td>409</td>
                    <td>Conflict – Email already exists</td>
                </tr>
                <tr>
                    <td>500</td>
                    <td>Internal Server Error – Unexpected issue</td>
                </tr>
                </tbody>
            </table>
        </section>

        <section id="notes" class="api-section">
            <h2>Notes</h2>
            <ul>
                <li>This API only supports the <code>POST</code> method.</li>
                <li><code>user_email</code> must be unique within the system.</li>
                <li><code>is_primary</code> can be set to <code>true</code> for the user's primary email.</li>
                <li>No authentication header is required for this API.</li>
            </ul>
        </section>

        <!-- Go to Top Button -->
        <a href="#top" class="go-top">↑ Top</a>
    </div>
</div>
</body>
</html>