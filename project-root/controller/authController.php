<?php
require_once __DIR__ . '/../model/AuthModel.php'; // Ensure path matches your folder structure

class AuthController {
    private $authModel;

    public function __construct() {
        $this->authModel = new AuthModel();
    }

    public function handleLogin($postData) {
        // 1. Sanitize
        $email = filter_var(trim($postData['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = $postData['password'] ?? '';

        // 2. Backend Validation
        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'All fields are strictly required.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format.'];
        }

        // 3. Authenticate via Model
        // FIX: Called 'authenticate' instead of 'loginUser'
        // FIX: Removed password_verify here because the Model already did it
        $authResult = $this->authModel->authenticate($email, $password);

        if ($authResult['success']) {
            $user = $authResult['data'];
            $role = $authResult['role']; // Model returns: 'admin', 'seller', or 'client'

            // Start Session
            if (session_status() === PHP_SESSION_NONE) session_start();
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = $role;
            // Handle username vs full_name differences across tables
            $_SESSION['username'] = $user['username'] ?? $user['full_name'] ?? 'User';
            $_SESSION['logged_in'] = true;

            // Determine Redirect
            // Note: Your model returns 'seller' for freelancers, so we match 'seller' here
            $redirect = match ($role) {
                'client'     => '../views/client/client_dashboard.php',
                'seller'     => '../views/freelancer/freelancer_dashboard.php', 
                'admin'      => '../views/admin/admin_dashboard.php',
                default      => '../index.php',
            };

            return ['success' => true, 'redirect' => $redirect];
        } else {
            // Pass the error message from the model (e.g., "Invalid email or password")
            return ['success' => false, 'message' => $authResult['message']];
        }
    }
}
?>