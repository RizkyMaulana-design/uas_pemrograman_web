<?php
class Database
{
    private $host = "127.0.0.1"; // Gunakan IP agar lebih stabil di Windows
    private $db_name = "db_uas_royal";
    private $username = "root";
    private $password = "";
    private $port = "3307"; // Port XAMPP kamu
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            // Perhatikan sintaks port=... di sini
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>