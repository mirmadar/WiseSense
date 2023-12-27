<?php
require_once 'models/User.php';
require_once 'models/Post.php';
require_once 'models/Friend.php';
require_once 'Database.php';

// Создаем подключение к базе данных
$database = new Database();

// Теперь убедимся, что у нас есть соединение с базой данных
if ($database->isConnected()) {
} else {
    echo "Ошибка при подключении к базе данных";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/styles/index.css">
    <title>WiseSense</title>
</head>
<body>
    <h1>WiseSense</h1>
    <p>Присоединяйтесь и делитесь своими мыслями!</p>
    <ul>
        <ul class="button-list">
            <li><a href="views/register.php" class="button">Зарегистрироваться</a></li>
            <li><a href="views/login.php" class="button">Войти</a></li>
        </ul>

    </ul>
</body>
</html>
