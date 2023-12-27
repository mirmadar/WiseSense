<?php
require_once '../Database.php';
require_once '../models/User.php';
require_once '../models/Friend.php';
require_once '../controllers/FriendController.php';

$database = new Database();

if (!$database->isConnected()) {
    die("Ошибка при подключении к базе данных");
}

$userModel = new User($database->getConnection());
$friendController = new FriendController(new Friend($database->getConnection()));

$foundUsers = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $searchTerm = $_POST['search_term'];

    // Выполните поиск пользователей и установите $foundUsers
    $foundUsers = $userModel->searchUsers($searchTerm);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/search_users.css">
    <title>Search Users</title>
</head>
<body>
<h2>WiseSense</h2>

<div class="search-container">
    <form action="" method="post">
        <label for="search_term">Поиск по пользователям:</label>
        <input type="text" name="search_term" required>
        <button type="submit" name="search">Искать</button>

    <?php if (!empty($foundUsers)) : ?>
            <h3>Найденные пользователи</h3>
            <ul>
                <?php foreach ($foundUsers as $user) : ?>
                    <li>
                        <?php echo "{$user['username']} 
                        (<a class='ssilka' href='view_profile.php?username={$user['username']}'>Посмотреть профиль </a>) | 
                        (<a class='ssilka' href='send_friend_request.php?userId={$user['id']}'>Добавить в друзья</a>)"; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else : ?>
        <p>Пользователи не найдены</p>
    <?php endif; ?>
</form>
<a class='button' href="profile.php">Моя страница</a>
</body>
</html>
