<?php
require_once '../models/User.php';
require_once '../models/Post.php';

class UserController {
    
    private $userModel;

    public function __construct($userModel) {
        $this->userModel = $userModel;
    }

    public function registerUser($userData) {
        // Обработка регистрации пользователя
        $username = $userData['username'];
        $password = $userData['password'];

        // Создание пользователя
        $result = $this->userModel->createUser($username, $password);

        if ($result) {
            echo "Пользователь успешно зарегистрирован.";
        } else {
            echo "Ошибка при регистрации пользователя.";
        }
    }

    public function loginUser($userData) {
        // Обработка входа пользователя
        $username = $userData['username'];
        $password = $userData['password'];

        // Попытка входа пользователя
        $user = $this->userModel->getUserByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            echo "Вход выполнен успешно.";
        } else {
            echo "Ошибка входа. Пожалуйста, проверьте свои учетные данные.";
        }
    }

    public function displayUserProfile($username) {
        // Отображение профиля пользователя
        $user = $this->userModel->getUserByUsername($username);
    
        if ($user) {
            // Получение постов пользователя
            $postsModel = new Post();
            $userPosts = $postsModel->getPosts($user['id']);
    
            // Отображение профиля и постов пользователя
            echo "Профиль пользователя: {$user['username']}\n";
            echo "Посты пользователя:\n";
            foreach ($userPosts as $post) {
                echo "- {$post['content']}\n";
            }
        } else {
            echo "Пользователь не найден.";
        }
    }
}
?>