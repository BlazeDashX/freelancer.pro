<?php
class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbname = "freelancer_proDB"; 
    
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
            
            // Check for specific connection errors
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }

            // Set charset to handle special characters (emojis, etc.)
            $this->conn->set_charset("utf8mb4");

        } catch (Exception $e) {
            // In a real app, you might log this to a file instead of echo
            echo "Connection Error: " . $e->getMessage();
        }

        return $this->conn;
    }
}
?>