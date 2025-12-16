<?php
class mydb {
    private $DBHostName = "localhost";
    private $DBUserName = "root";
    private $DBPassword = "";
    private $DBName     = "webtech_project";
    private $tableName  = "clients"; 

    public function createConObject() {
        $conn = new mysqli($this->DBHostName, $this->DBUserName, $this->DBPassword, $this->DBName);
        
        if ($conn->connect_error) {
            // Throw specific error for connection failure
            throw new Exception("Database connection failed. Check server status.");
        }
        
        $conn->set_charset('utf8mb4');  
        return $conn;
    }

    public function registerClient($conn, $full_name, $email, $password_hash, $phone, $username, $profile_picture_path, $payment) {
        try {
            // 1. CHECK FOR DUPLICATE EMAIL OR USERNAME (Combined Check)
            $sqlCheck = "SELECT email, username FROM `{$this->tableName}` WHERE email = ? OR username = ?";
            $check = $conn->prepare($sqlCheck);
            
            if (!$check) {
                throw new Exception("Prepare failed (check): " . $conn->error);
            }
            
            $check->bind_param("ss", $email, $username);
            $check->execute();
            $result = $check->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $check->close();

                // Return specific error based on which field caused the collision
                
                if ($row['username'] === $username && $row['email'] === $email) {
                    return ["success" => false, "message" => "This username is already taken. This email is already registered."];
                }
                if ($row['email'] === $email) {
                    return ["success" => false, "message" => "This email is already registered."];
                }
                if ($row['username'] === $username) {
                    return ["success" => false, "message" => "This username is already taken."];
                }
            }
            $check->close();

            // 2. Insert new user
            $sqlInsert = "INSERT INTO `{$this->tableName}` 
                (full_name, email, password, phone, username, profile_picture, payment) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
                
            $stmt = $conn->prepare($sqlInsert);
            $stmt->bind_param("sssssss", $full_name, $email, $password_hash, $phone, $username, $profile_picture_path, $payment);

            if ($stmt->execute()) {
                $stmt->close();
                return ["success" => true];
            } else {
                $err = $stmt->error;
                $stmt->close();
                return ["success" => false, "message" => "Database Insert failed: " . $err];
            }

        } catch (Exception $e) {
            return ["success" => false, "message" => "System Error: " . $e->getMessage()];
        }
    }
}
?>