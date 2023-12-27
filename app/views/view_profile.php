<?php
// Здесь должны быть необходимые подключения к базе данных и классам
require_once '../Database.php';
require_once '../models/User.php';
require_once '../models/Post.php';

$database = new Database();

if (!$database->isConnected()) {
    die("Ошибка при подключении к базе данных");
}

$userModel = new User($database->conn);
$postModel = new Post($database->conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/view_profile.css"> <!-- Подключение файла стилей -->
    <title>User Profile</title>
</head>
<body>
<h2>WiseSense</h2>
<div class="view_profile-container">
    <?php
    // Получаем никнейм пользователя из параметров запроса
    if (isset($_GET['username'])) {
        $username = $_GET['username'];
        $userData = $userModel->getUserByUsername($username);

        if ($userData) {
            // Отображаем информацию о пользователе
            echo "<h3><span>Профиль пользователя {$userData['username']}</span></h3>";
            echo "<p>Email: {$userData['email']}</p>";

            // Отображаем посты пользователя
            $userPosts = $postModel->getPostsByUserId($userData['id']);
            if (!empty($userPosts)) {
                echo "<h3><span>Публикации</span></h3>";
                echo "<ul>";
                foreach ($userPosts as $post) {
                    echo "<li>{$post['content']} <br><br> ({$post['created_at']})</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>Этот пользователь еще ничего не опубликовал</p>";
            }

        } else {
            echo "<p>Пользователь не найден</p>";
        }
    } else {
        echo "<p>Неверные параметры запроса</p>";
    }
    ?>
</div>
<div>
    <!-- Кнопка для возврата на страницу поиска -->
    <a href="search_users.php" class="button">На страницу поиска</a>

    <!-- Кнопка для возврата на главную страницу профиля -->
    <a href="profile.php" class="button">На главную страницу</a>
</div>
</body>
</html>