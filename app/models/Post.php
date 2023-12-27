<?php
// models/Post.php
require_once __DIR__ . '/../Database.php';

class Post {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createPost($userId, $content) {
        $stmt = $this->db->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
        $stmt->bind_param("is", $userId, $content);
        
        return $stmt->execute();
    }

    public function getPostById($postId) {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        return $result->fetch_assoc();
    }
    

    public function editPost($postId, $content) {
        $stmt = $this->db->prepare("UPDATE posts SET content = ? WHERE id = ?");
        $stmt->bind_param("si", $content, $postId);
        
        return $stmt->execute();
    }

    public function deletePost($postId) {
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $postId);
        
        return $stmt->execute();
    }

    public function getPostsByUserId($userId) {
        $posts = [];

        $stmt = $this->db->prepare("SELECT posts.id, posts.content, posts.created_at, users.username 
                                   FROM posts
                                   INNER JOIN users ON posts.user_id = users.id
                                   WHERE users.id = ?
                                   ORDER BY posts.created_at DESC"); // Добавлен ORDER BY для сортировки по времени создания
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }

        return $posts;
    }
}
?>
