<?php
session_start();
require_once '../Database.php';
require_once '../models/Friend.php';
require_once '../controllers/FriendController.php';
require_once '../models/User.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/send.css"> <!-- Замените "your_styles.css" на путь к вашему файлу со стилями -->
    <title>Send Friend Request</title>
</head>
<body>
<h2>WiseSense</h2>

<?php
$database = new Database();

if (!$database->isConnected()) {
    die("Ошибка при подключении к базе данных");
}

$friendController = new FriendController(new Friend($database->getConnection()));
$userModel = new User($database->getConnection());

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['userId'])) {
    $toUserId = $_GET['userId'];

    if (isset($_SESSION['user_id'])) {
        $fromUserId = $_SESSION['user_id'];

        $result = $friendController->sendFriendRequest($fromUserId, $toUserId);

        if ($result) {
            echo "<p>Запрос на добавление в друзья отправлен</p> <br>";
            echo "<a href='search_users.php'>Поиск</a><br>";
            echo "<a href='profile.php'>Моя страница</a>";
        } else {
            echo "<p> Ошибка при отправке запроса на добавление в друзья.</p><br>";
            echo "<a href='search_users.php'>Поиск</a><br>";
            echo "<a href='profile.php'>Моя страница</a>";
        }
    } else {
        echo "Ошибка: Пользователь не аутентифицирован.";
    }
}
?>

</body>
</html>
