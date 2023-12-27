<?php
session_start();
require_once '../Database.php';
require_once '../models/User.php';
require_once '../models/Post.php';
require_once '../models/Friend.php';

$database = new Database();
if (!$database->isConnected()) {
    die("Ошибка при подключении к базе данных");
}

$connection = $database->getConnection(); 
$friendModel = new Friend($connection); 
$userModel = new User($connection);

$userData = $userModel->getUserById($_SESSION['user_id']); 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_post'])) {
    $content = $_POST['post_content'];
    if (!empty($content)) {
        $postModel = new Post($database->getConnection());
        $result = $postModel->createPost($userData['id'], $content);
        if ($result) {
            header("Location: profile.php"); 
            exit();
        } else {
            echo "Ошибка при создании поста." . $database->getConnection()->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/profile.css">
    <title>User Profile</title>
</head>
<body>

<h2>WiseSense</h2>
<div class="profile-container">
    <div class="profile-data">
        <h3><span>Мой профиль</span></h3>
    <p><?php echo $userData['username']; ?></p>
    <p><?php echo $userData['email']; ?></p>
            <!-- Вывод постов пользователя -->
            <?php
            $postModel = new Post($database->getConnection());
            $posts = $postModel->getPostsByUserId($userData['id']);

            if (!empty($posts)) {
                echo "<h3><span>Публикации</span></h3>";
                echo "<ul>";
                foreach ($posts as $post) {
                    echo "<li>{$post['content']} <br> ({$post['created_at']}) <a href='edit_post.php?post_id={$post['id']}&username={$userData['username']}'>Редактировать</a><br></li>";
                }
                echo "<br></ul>";
            } else {
                echo "<p>Вы еще не опубликовали посты</p>";
            }
            ?>
        <form action="profile.php" method="post" class="post_creation">
            <label for="post_content"></label>
            <textarea name="post_content" required></textarea>
            <button type="submit" name="create_post">Опубликовать</button>
        </form>
    </div>
    <div class="friends-requests">
        <h3><span>Мои друзья   <a href='search_users.php'><img src='styles/images/icons8-поиск-24.png' alt='Иконка поиска друзей'></a></span></h3>

        <!--    <a href='search_users.php'>Найти друзей</a>-->
    <ul>
        <?php
        $friendsList = $userModel->getFriendsList($userData['id']);
        if (!empty($friendsList)) {
            foreach ($friendsList as $friend) {
                $friendData = $userModel->getUserById($friend['friend_id']);
                echo "<li class='list_friends'><a href='view_profile.php?username=" . urlencode($friendData['username'] ?? '') ."'>" . htmlspecialchars($friendData['username'] ?? 'N/A') . "</a></li>";
            }
        } else {
            echo "<p>У вас нет друзей.</p>";
        }
        ?>
    </ul>
    <h3><span>Входящие заявки</span></h3>
    <ul>
        <?php
        $incomingRequests = $friendModel->getFriendRequests($userData['id']);
        if (!empty($incomingRequests)) {
            foreach ($incomingRequests as $request) {
                $requester = $userModel->getUserById($request['from_user_id']);
                echo "<li>" . htmlspecialchars($requester['username'] ?? 'N/A') . " 
                  (<a href='accept_friend_request.php?requestId={$request['id']}'>Принять</a>)
                  (<a href='reject_friend_request.php?requestId={$request['id']}'>Отклонить</a>)</li>";
            }
        } else {
            echo "<p>Нет новых заявок</p>";
        }
        ?>
    </ul>
    </div>
</div>
<form action="../index.php" method="post" class="form_logout">
    <button type="submit" name="logout" style="background: none; border: none; cursor: pointer;">
        <img src="styles/images/icons8-выход-48.png" alt="Выход" class="logout-button">
    </button>
</form>
</body>
</html>