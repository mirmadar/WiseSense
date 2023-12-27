<?php
require_once 'models/Post.php';

class PostController {
    private $postsModel;

    public function __construct($postsModel) {
        $this->postsModel = $postsModel;
    }

    public function createPost($userId, $postContent) {
        // Создание поста
        $result = $this->postsModel->createPost($userId, $postContent);

        if ($result) {
            echo "Пост успешно создан.";
        } else {
            echo "Ошибка при создании поста.";
        }
    }

    public function editPost($postId, $newContent) {
        // Редактирование поста
        $result = $this->postsModel->editPost($postId, $newContent);

        if ($result) {
            echo "Пост успешно отредактирован.";
        } else {
            echo "Ошибка при редактировании поста.";
        }
    }

    public function deletePost($postId) {
        // Удаление поста
        $result = $this->postsModel->deletePost($postId);

        if ($result) {
            echo "Пост успешно удален.";
        } else {
            echo "Ошибка при удалении поста.";
        }
    }
}
?>
