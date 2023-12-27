<!-- edit_post.php -->
<?php
require_once '../Database.php';
require_once '../models/User.php';
require_once '../models/Post.php';
require_once '../controllers/UserController.php';

$database = new Database();

if (!$database->isConnected()) {
    die("Ошибка при подключении к базе данных");
}

$userModel = new User($database->conn);

// Проверяем, определен ли параметр post_id в запросе
if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    // Получаем данные о посте
    $postModel = new Post($database->getConnection());
    $post = $postModel->getPostById($post_id);

    // Проверяем, определены ли $post и $postModel
    if (!isset($post) || !isset($postModel)) {
        echo "Post data or controller not available";
        exit;
    }
} else {
    // Если параметр post_id не указан, выдаем ошибку
    echo "Post ID not specified";
    exit;
}

// Проверяем, была ли отправлена форма редактирования поста
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_post'])) {
    $new_content = $_POST['new_content'];

    // Проверяем, что новое содержание поста не пусто
    if (!empty($new_content)) {
        $result = $postModel->editPost($post['id'], $new_content);

        if ($result) {
            // echo "Пост успешно отредактирован.";
            // Перенаправляем обратно на страницу профиля после успешного редактирования
            if (isset($_GET['username'])) {
                $redirect_url = "profile.php?username={$_GET['username']}";
                echo "<script>window.location.href='$redirect_url';</script>";
                exit();
            }
        } else {
            echo "Ошибка при редактировании поста.";
        }
    } else {
        echo "Новое содержание поста не может быть пустым.";
    }
}

    // Проверяем, была ли отправлена форма удаления поста
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_post'])) {
    $result = $postModel->deletePost($post['id']);

    if ($result) {
        // Пост успешно удален, перенаправляем обратно на страницу профиля
        if (isset($_GET['username'])) {
            $redirect_url = "profile.php?username={$_GET['username']}";
            echo "<script>window.location.href='$redirect_url';</script>";
            exit();
        }
    } else {
        echo "Ошибка при удалении поста.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/edit.css"> <!-- Замените "your_styles.css" на путь к вашему файлу со стилями -->
    <title>Edit Post</title>
</head>
<body>
<h2>WiseSense</h2>
<!-- Форма для редактирования поста -->
<div class="edit_place">
<form action="" method="post">
    <label for="new_content"></label>
    <textarea name="new_content" required><?php echo $post['content']; ?></textarea>
    <button type="submit" name="edit_post">Сохранить</button>
    <button type="submit" name="delete_post">Удалить</button>
</form>
</div>
</body>
</html>
