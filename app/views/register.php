<?php
require_once '../Database.php';
require_once '../models/user.php';

// Создаем подключение к базе данных
$database = new Database();

// Теперь убедимся, что у нас есть соединение с базой данных
if (!$database->isConnected()) {
    die("Ошибка при подключении к базе данных");
}

$userModel = new User($database->conn); // Передаем соединение с базой данных в конструктор модели пользователя

// Проверка, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из формы
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"]; // Добавлена строка для получения email

    // Валидация данных (добавьте вашу логику валидации)

    // Попытка регистрации пользователя
    $result = $userModel->createUser($username, $password, $email);

    if ($result) {
        // Регистрация прошла успешно, перенаправление на страницу login
        header("Location: login.php");
        exit();
    } else {
        // Вывод сообщения об ошибке
        $error_message = "Не удалось зарегистрироваться. Пожалуйста, попробуйте снова.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/register.css">
    <title>Register</title>
</head>
<body>
    <h2>Регистрация</h2>

    <?php
    // Вывод сообщения об ошибке (если есть)
    if (isset($error_message)) {
        echo "<p>{$error_message}</p>";
    }
    ?>

    <form action="register.php" method="post">
        <label for="username">Имя пользователя:</label>
        <input type="text" name="username" required>

        <label for="password">Пароль:</label>
        <input type="password" name="password" required>

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <button type="submit">Зарегистрироваться</button>
    </form>
</body>
</html>
