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

    $database = new Database();
    if (!$database->isConnected()) {
        die("Ошибка при подключении к базе данных");
    }

$friendModel = new Friend($database->getConnection());

    $success = $friendModel->rejectFriendRequest($requestId);

    if ($success) {
        header("Location: profile.php"); // Перенаправление на страницу профиля
        exit();
    } else {
        echo "Ошибка при отклонении запроса.";
    }
}
?>
