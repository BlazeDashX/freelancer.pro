<?php
// Prevent direct access
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 403 Forbidden");
    exit;
}

require_once 'authcontroller.php';

// Instantiate Controller
$auth = new AuthController();

// Process Request
$result = $auth->handleLogin($_POST);

// Return JSON
header('Content-Type: application/json');
echo json_encode($result);
exit;
?>