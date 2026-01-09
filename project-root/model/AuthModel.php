<?php
require_once __DIR__ . '/db.php';

class AuthModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function authenticate($email, $password) {
        // 1. Check ADMINS Table
        $admin = $this->checkTable('admins', $email); // Assuming table is named 'admins'
        if ($admin && password_verify($password, $admin['password'])) {
            return ['success' => true, 'role' => 'admin', 'data' => $admin];
        }

        // 2. Check FREELANCERS Table
        $freelancer = $this->checkTable('freelancers', $email); // Assuming table is named 'freelancers'
        if ($freelancer && password_verify($password, $freelancer['password'])) {
            return ['success' => true, 'role' => 'seller', 'data' => $freelancer];
        }

        // 3. Check CLIENTS Table
        $client = $this->checkTable('clients', $email);
        if ($client && password_verify($password, $client['password'])) {
            return ['success' => true, 'role' => 'client', 'data' => $client];
        }

        // 4. Not found anywhere
        return ['success' => false, 'message' => 'Invalid email or password.'];
    }

    // Helper function to keep code DRY (Don't Repeat Yourself)
    private function checkTable($tableName, $email) {
        // Adjust column names if yours are different (e.g., 'full_name' vs 'name')
        $sql = "SELECT * FROM `$tableName` WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 1) {
                return $result->fetch_assoc();
            }
        }
        return false;
    }
}
?>
