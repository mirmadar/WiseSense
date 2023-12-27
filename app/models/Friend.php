<?php
require_once __DIR__ . '/../Database.php';

class Friend {
    private $db;

    public function __construct($connection) {
        $this->db = $connection;
    }

    public function sendFriendRequestIfNotExists($fromUserId, $toUserId) {
        $stmt = $this->db->prepare("SELECT id FROM friend_requests WHERE from_user_id = ? AND to_user_id = ?");
        $stmt->bind_param("ii", $fromUserId, $toUserId);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows == 0) {
            // Запроса еще не существует, добавляем его
            $stmt = $this->db->prepare("INSERT INTO friend_requests (from_user_id, to_user_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $fromUserId, $toUserId);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } else {
            // Запрос уже существует, не добавляем его
            return false; // Возвращаем булево значение вместо вывода сообщения
        }
    }
    
    public function updateFriendRequestStatus($requestId, $status) {
        $stmt = $this->db->prepare("UPDATE friend_requests SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $requestId);

        return $stmt->execute();
    }

    public function getFriendRequests($userId) {
        $stmt = $this->db->prepare("SELECT id, from_user_id FROM friend_requests WHERE to_user_id = ? AND status = 'pending'");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $requests = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $requests;
    }

    public function acceptFriendRequest($requestId) {
        $stmt = $this->db->prepare("UPDATE friend_requests SET status = 'accepted' WHERE id = ?");
        $stmt->bind_param("i", $requestId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function rejectFriendRequest($requestId) {
        return $this->updateFriendRequestStatus($requestId, 'rejected');
    }
    
}
?>
