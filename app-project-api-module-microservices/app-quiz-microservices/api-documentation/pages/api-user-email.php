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
            <a href="#select-output">Select Output</a>
            <a href="#error-codes">Error Codes</a>
            <a href="#notes">Notes</a>
        </div>

        <section id="endpoint" class="api-section">
            <h2>API Endpoint <span class="method-tag">POST</span></h2>
            <code><?= dirname($baseUrl) . "/app-microservice-user-email/"; ?></code>
        </section>

        <section id="overview" class="api-section">
            <h2>Overview</h2>
            <p>This API registers or updates a user's email address. Supports <code>insert</code> and <code>select</code> operations.</p>
        </section>

        <section id="request-body" class="api-section">
            <h2>Request Body</h2>
            <pre>{
  "user_id": "989898",
  "user_email": "email@rzrasel.com",
  "email_provider": "user",
  "is_primary": "true",
  "verification_code": "",
  "action_type": "insert"
}</pre>
        </section>

        <section id="success-response" class="api-section">
            <h2>Success Response <span class="status-code">201 Created</span></h2>
            <pre>{
  "message": "User email created successfully.",
  "status": "success",
  "status_code": 201,
  "data": {
    "user_id": "989898",
    "id": "174663959427574745",
    "user_email": "email@rzrasel.com",
    "email_provider": "user",
    "is_primary": false,
    "last_verification_sent_at": null,
    "verification_code_expiry": null,
    "verification_status": "pending",
    "status": "active"
  }
}</pre>
        </section>

        <section id="error-405" class="api-section">
            <h2>Error: Method Not Allowed <span class="status-code">405</span></h2>
            <pre>{
  "message": "Only POST method is allowed",
  "status": "error",
  "status_code": 405,
  "data": null
}</pre>
        </section>

        <section id="error-409" class="api-section">
            <h2>Error: Duplicate Email <span class="status-code">409</span></h2>
            <pre>{
  "message": "'email@rzrasel.com' email already exists.",
  "status": "error",
  "status_code": 409,
  "data": {
    "user_id": "989898",
    "id": "174663961172292734",
    "user_email": "email1@rzrasel.com",
    "email_provider": "user",
    "is_primary": true,
    "last_verification_sent_at": null,
    "verification_code_expiry": null,
    "verification_status": "pending",
    "status": "active"
  }
}</pre>
        </section>

        <section id="select-output" class="api-section">
            <h2>Select Operation Output</h2>
            <p>Request:</p>
            <pre>{
  "user_id": "989898",
  "user_email": "email@rzrasel.com",
  "email_provider": "user",
  "is_primary": "false",
  "verification_code": "",
  "status": "active",
  "action_type": "select",
  "columns": ["user_id", "status"]
}</pre>
            <p>Response:</p>
            <pre>{
  "message": "User email selected successfully.",
  "status": "success",
  "status_code": 200,
  "data": [
    {
      "user_id": "989898",
      "id": "174662444594497923",
      "user_email": "email@rzrasel.com",
      "email_provider": "user",
      "is_primary": "",
      "last_verification_sent_at": null,
      "verification_code_expiry": null,
      "verification_status": "pending",
      "status": "active"
    },
    {
      "user_id": "989898",
      "id": "174663959427574745",
      "user_email": "email1@rzrasel.com",
      "email_provider": "user",
      "is_primary": "",
      "last_verification_sent_at": null,
      "verification_code_expiry": null,
      "verification_status": "pending",
      "status": "active"
    }
  ]
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
                <tr><td>400</td><td>Bad Request – Missing/Invalid params</td></tr>
                <tr><td>405</td><td>Method Not Allowed – Only POST allowed</td></tr>
                <tr><td>409</td><td>Conflict – Email already exists</td></tr>
                <tr><td>500</td><td>Internal Server Error</td></tr>
                </tbody>
            </table>
        </section>

        <section id="notes" class="api-section">
            <h2>Notes</h2>
            <ul>
                <li><code>POST</code> method only</li>
                <li>Email must be unique</li>
                <li><code>is_primary</code> can be set to true</li>
                <li>No authorization required</li>
            </ul>
        </section>

        <a href="#top" class="go-top">↑ Top</a>
    </div>
</div>
</body>
</html>