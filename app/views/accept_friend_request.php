<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../Database.php';
require_once '../models/Friend.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['requestId'])) {
    $requestId = $_GET['requestId'];

    // Предполагая, что у вас есть корректная реализация класса Database
    $database = new Database();
    $connection = $database->getConnection();

    // Предполагая, что конструктор Friend требует подключение к базе данных
    $friendModel = new Friend($connection);

    $success = $friendModel->acceptFriendRequest($requestId);

    if ($success) {
        header("Location: profile.php"); // Перенаправление на страницу профиля
        exit();
    } else {
        echo "Ошибка при принятии запроса.";
    }
}
?>
