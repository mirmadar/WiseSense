<?php
require_once '../models/Friend.php';

class FriendController {
    private $friendsModel;

    public function __construct(Friend $friendsModel) {
        $this->friendsModel = $friendsModel;
    }

    public function searchFriends($searchTerm) {
        $foundFriends = $this->friendsModel->searchFriends($searchTerm);

        if ($foundFriends) {
            Friend::showSearchResults($foundFriends);
        } else {
            echo "Друзья не найдены.";
        }
    }

    public function sendFriendRequest($fromUserId, $toUserId) {
        $result = $this->friendsModel->sendFriendRequestIfNotExists($fromUserId, $toUserId);
        return $result; // Возвращаем результат без вывода сообщения
    }
    
public function acceptFriendRequest($requestId) {
    // Обновление статуса запроса на добавление в друзья на "accepted"
    // Примерно такой же метод можно добавить для отклонения запроса
    $result = $this->friendsModel->updateFriendRequestStatus($requestId, 'accepted');

    if ($result) {
        echo "Запрос на добавление в друзья принят.";
    } else {
        echo "Ошибка при обработке запроса на добавление в друзья.";
    }
}

public function displayFriendRequests($userId) {
    // Получение всех запросов на добавление в друзья для данного пользователя
    $requests = $this->friendsModel->getFriendRequests($userId);

    // Отображение запросов на добавление в друзья
    echo "<h2>Friend Requests</h2>";
    if (!empty($requests)) {
        echo "<ul>";
        foreach ($requests as $request) {
            $requester = $this->userModel->getUserById($request['from_user_id']);
            echo "<li>{$requester['username']} 
                  (<a href='accept_friend_request.php?requestId={$request['id']}'>Accept</a>)
                  (<a href='reject_friend_request.php?requestId={$request['id']}'>Reject</a>)</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No pending friend requests.</p>";
    }
}
}
?>
