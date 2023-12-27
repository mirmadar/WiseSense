<?php
class Database {
    private $host = 'db';
    private $dbname = 'snet';
    private $username = 'user';
    private $password = 'password';
    public $conn;

    public function __construct() {
        // Устанавливаем соединение с базой данных
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        // Проверяем соединение
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function isConnected() {
        return $this->conn->ping(); // Проверяем, установлено ли соединение
    }

    public function getConnection() {
        return $this->conn; // Возвращаем текущее соединение
    }
}

?>
