<?php
require_once __DIR__ . '/db.php';

class ClientModel {
    private $conn;
    private $tableName = "clients";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // ==========================================
    // 1. REGISTRATION LOGIC
    // ==========================================
    public function register($full_name, $email, $password, $phone, $username, $profile_picture_path, $payment) {
        try {
            // Check for duplicates
            if ($this->checkDuplicate($email, $username)) {
                return ["success" => false, "message" => "Email or Username already exists."];
            }

            // Insert new client
            $sql = "INSERT INTO `{$this->tableName}` 
                    (full_name, email, password, phone, username, profile_picture, payment) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->conn->error);
            }

            // Note: The password should already be hashed before calling this, 
            // OR you can hash it here. Assuming it is passed as a hash:
            $stmt->bind_param("sssssss", $full_name, $email, $password, $phone, $username, $profile_picture_path, $payment);

            if ($stmt->execute()) {
                return ["success" => true, "message" => "Registration successful"];
            } else {
                return ["success" => false, "message" => "Database error: " . $stmt->error];
            }

        } catch (Exception $e) {
            return ["success" => false, "message" => "System Error: " . $e->getMessage()];
        }
    }

    // ==========================================
    // 2. LOGIN LOGIC
    // ==========================================
    public function login($email, $passwordRaw) {
        try {
            $sql = "SELECT id, full_name, username, password, profile_picture FROM `{$this->tableName}` WHERE email = ?";
            $stmt = $this->conn->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Database error: " . $this->conn->error);
            }

            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                
                // Verify the hashed password
                if (password_verify($passwordRaw, $user['password'])) {
                    // Remove password from the array before returning it to the controller
                    unset($user['password']); 
                    return ["success" => true, "data" => $user];
                } else {
                    return ["success" => false, "message" => "Invalid password."];
                }
            }

            return ["success" => false, "message" => "Email not found."];

        } catch (Exception $e) {
            return ["success" => false, "message" => "Login Error: " . $e->getMessage()];
        }
    }

    // ==========================================
    // 3. GET PROFILE (By ID)
    // ==========================================
    public function getProfileById($id) {
        $sql = "SELECT id, full_name, email, phone, username, profile_picture, payment, created_at 
                FROM `{$this->tableName}` WHERE id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            return ["success" => true, "data" => $user];
        } else {
            return ["success" => false, "message" => "User not found."];
        }
    }

    // ==========================================
    // HELPER: DUPLICATE CHECK
    // ==========================================
    private function checkDuplicate($email, $username) {
        $sql = "SELECT id FROM `{$this->tableName}` WHERE email = ? OR username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows > 0;
    }
}
?>