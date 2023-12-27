<?php
require_once __DIR__ . '/../Database.php';

class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createUser($username, $password, $email) {
        // Проверка уникальности почты и никнейма
        $sql_check = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt_check = $this->db->prepare($sql_check);
        $stmt_check->bind_param("ss", $username, $email);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            return false; // Возвращаем false, если почта или никнейм уже зарегистрированы
        }

        // Логика создания пользователя (если почта и никнейм уникальны)
        $sql_create = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt_create = $this->db->prepare($sql_create);
        $stmt_create->bind_param("sss", $username, $password, $email);
        $result_create = $stmt_create->execute();

        if ($result_create) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserById($userId) {
        $stmt = $this->db->prepare("SELECT id, username, email FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
    
        return $user;
    }

    // Получение идентификатора пользователя по имени пользователя
    public function getUserId($username) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $row = $result->fetch_assoc();
        return $row['id'];
    }    

    public function getUserByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function loginUser($username, $password) {
        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Для аутентификации пользователя
    public function authenticateUser($username, $password) {
        // Проверяем учетные данные в базе данных
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        // Проверяем пароль
        if ($user && password_verify($password, $user['password'])) {
            return $user; // Возвращаем данные пользователя
        } else {
            return null; // Неверные учетные данные
        }
    }

    public function getFriendsList($userId) {
        $stmt = $this->db->prepare("
            SELECT 
                CASE
                    WHEN from_user_id = ? THEN to_user_id
                    ELSE from_user_id
                END AS friend_id
            FROM friend_requests
            WHERE (from_user_id = ? OR to_user_id = ?) AND status = 'accepted'
        ");
        $stmt->bind_param("iii", $userId, $userId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $friendsList = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $friendsList;
    }

    public function searchUsers($searchTerm) {
        $searchTerm = '%' . $searchTerm . '%';

        $stmt = $this->db->prepare("SELECT * FROM users WHERE username LIKE ?");
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();

        $result = $stmt->get_result();
        $users = [];

        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        $stmt->close();

        return $users;
    }
}
?>