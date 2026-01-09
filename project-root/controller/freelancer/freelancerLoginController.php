<?php
// START SESSION: Critical for passing errors/data back to the form
session_start();

require_once '../../model/clientRegDb.php';

// --- 1. SECURITY & ACCESS CONTROL ---
// Block anyone trying to open this file directly without submitting the form
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['register'])) {
    header("HTTP/1.1 403 Forbidden");
    exit("Access Denied");
}

// Initialize error array and capture all input data
$errors = [];
$data = $_POST; 

// --- 2. INPUT SANITIZATION & VALIDATION ---
$full_name        = trim($data['full_name'] ?? '');
$email            = trim($data['email'] ?? '');
$phone            = trim($data['phone'] ?? '');
$username         = trim($data['username'] ?? '');
$password         = $data['password'] ?? '';
$confirm_password = $data['confirm_password'] ?? '';
$payment          = $data['payment'] ?? '0';
$terms_agreed     = isset($data['terms']);

// Validate Rules
if (strlen($full_name) < 3)       $errors['full_name'] = "Name must be at least 3 characters.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Invalid email format.";
if ($phone !== "" && !preg_match('/^\d+$/', $phone)) $errors['phone'] = "Phone must be digits only.";
if (strlen($username) < 4)        $errors['username'] = "Username must be at least 4 characters.";
if (strlen($password) < 6)        $errors['password'] = "Password must be at least 6 characters.";
if ($password !== $confirm_password) $errors['confirm_password'] = "Passwords do not match.";
if (!$terms_agreed)               $errors['terms'] = "You must agree to the Terms of Service.";

// --- 3. SECURE FILE UPLOAD ---
$profile_picture_path = "default.jpg"; // Default if no file uploaded

// Only attempt upload if there are no other validation errors so far
if (empty($errors) && !empty($_FILES['profile_picture']['name'])) {
    $upload_dir = "../../uploads/";
    
    // Create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Security: Generate a unique name to prevent overwriting or hacking
    $file_name = uniqid('user_') . "_" . basename($_FILES['profile_picture']['name']);
    $target = $upload_dir . $file_name;
    $fileType = strtolower(pathinfo($target, PATHINFO_EXTENSION));
    
    // Security: Whitelist allowed extensions
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (!in_array($fileType, $allowed_types)) {
        $errors['profile_picture'] = "Only JPG, JPEG, PNG, & GIF files are allowed.";
    } elseif (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target)) {
        $errors['profile_picture'] = "File upload failed due to server error.";
    } else {
        $profile_picture_path = $target;
    }
}

// --- 4. ERROR HANDLING (FLASH DATA) ---
if (!empty($errors)) {
    // Store errors AND old input data in Session
    $_SESSION['errors'] = $errors;
    $_SESSION['old_inputs'] = $data; 
    
    // Redirect back to the registration page
    header("Location: ../../views/client/client_Reg.php");
    exit;
}

// --- 5. DATABASE OPERATIONS ---
$db = new mydb();

try {
    $conn = $db->createConObject();
    
    // Security: Hash the password before storage
    $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
    
    $result = $db->registerClient(
        $conn, 
        $full_name, 
        $email, 
        $hashed_pass, 
        $phone, 
        $username, 
        $profile_picture_path, 
        $payment
    );

    if ($result['success']) {
        // --- SUCCESS LOGIC UPDATED HERE ---
        // Set the success flag so client_Reg.php knows to show the popup
        $_SESSION['reg_success'] = true;
        
        // Clear old inputs (form should be empty now)
        unset($_SESSION['old_inputs']);
        unset($_SESSION['errors']);
        
        // Redirect back to the FORM, not a new page
        header("Location: ../../views/client/client_Reg.php");
        exit;
    } else {
        // DB FAILURE (e.g., Email already exists)
        $_SESSION['errors']['general'] = $result['message'];
        $_SESSION['old_inputs'] = $data;
        header("Location: ../../views/client/client_Reg.php");
        exit;
    }

} catch (Exception $e) {
    // SYSTEM FAILURE
    error_log("Registration Error: " . $e->getMessage()); // Log internally
    $_SESSION['errors']['general'] = "A system error occurred. Please try again later.";
    header("Location: ../../views/client/client_Reg.php");
    exit;
}
?>