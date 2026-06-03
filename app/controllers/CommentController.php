<?php
// handles comment form posts from article pages

class CommentController extends Controller {

    private Comment $commentModel;

    public function __construct() {
        $this->commentModel = new Comment();
    }

    public function store(string $articleId): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
        }

        $this->validateCsrf();

        $id         = (int) $articleId;
        $authorName = trim($_POST['author_name'] ?? '');
        $content    = trim($_POST['content']     ?? '');

        if (empty($authorName) || empty($content)) {
            $this->setFlash('danger', 'Name and comment are required.');
            $this->redirect('/article/' . $id);
        }

        if (strlen($authorName) > 100) {
            $this->setFlash('danger', 'Name is too long (maximum 100 characters).');
            $this->redirect('/article/' . $id);
        }

        $this->commentModel->create($id, $authorName, $content);
        $this->setFlash('success', 'Your comment has been submitted and is awaiting moderation.');
        $this->redirect('/article/' . $id);
    }
}
