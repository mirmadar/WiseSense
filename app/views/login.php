<?php
session_start(); // Начать сессию
require_once '../Database.php';
require_once '../models/User.php';

// Создаем подключение к базе данных
$database = new Database();

if ($database->isConnected()) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $userModel = new User($database->getConnection()); // Создание объекта класса User и передача соединения с базой данных в конструктор

        $result = $userModel->loginUser($username, $password); // Попытка входа пользователя

        if ($result) {
            $_SESSION['user_id'] = $userModel->getUserId($username);// Сохранение идентификатора пользователя в сессии
            $_SESSION['username'] = $username; // Сохранение имени пользователя в сессии
            header("Location: profile.php"); // Перенаправление на страницу профиля
            exit();
        } else {
            $error_message = "Неверное имя пользователя или пароль";
        }
    }
} else {
    die("Ошибка при подключении к базе данных");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/register.css">
    <title>Login</title>
</head>
<body>
    <h2>Авторизация</h2>
    <?php
    // Вывод сообщения об ошибке (если есть)
    if (isset($error_message)) {
        echo "<p>{$error_message}</p>";
    }
    ?>
    <form action="login.php" method="post">
        <label for="username">Имя пользователя:</label>
        <input type="text" name="username" required>

        <label for="password">Пароль:</label>
        <input type="password" name="password" required>

        <button type="submit">Войти</button>
    </form>
</body>
</html>