<?php
session_start();
require_once '../model/clientModel.php';

// 1. Initialize Response Array
$response = ['status' => 'error', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 2. Sanitize Inputs
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // 3. PHP Validation (Backend Gatekeeper)
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: ../views/login.php");
        exit;
    }

    // 4. Call the Model
    $clientModel = new ClientModel();
    $authResult = $clientModel->loginClient($email, $password);

    if ($authResult['success']) {
        // 5. Set Session
        $user = $authResult['data'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = 'client';
        $_SESSION['logged_in'] = true;

        // 6. Success Redirect (Dashboard)
        header("Location: ../views/client/client_dashboard.php");
        exit;
    } else {
        // 7. Error Handling
        $_SESSION['error'] = $authResult['message'];
        header("Location: ../views/login.php");
        exit;
    }
} else {
    // If someone tries to access this file directly without POST
    header("Location: ../views/login.php");
    exit;
}
?>