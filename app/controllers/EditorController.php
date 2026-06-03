<?php
// editor only - approve/reject articles and moderate comments

class EditorController extends Controller {

    private Article $articleModel;
    private Comment $commentModel;
    private Tag     $tagModel;

    public function __construct() {
        $this->articleModel = new Article();
        $this->commentModel = new Comment();
        $this->tagModel     = new Tag();
    }

    public function dashboard(): void {
        $this->requireRole('editor');

        $articles = $this->articleModel->getAllForEditor();
        $flash    = $this->getFlash();

        $pendingArticles  = count(array_filter($articles, fn($a) => $a['status'] === 'pending'));
        $pendingComments  = count($this->commentModel->getAllPending());

        $this->view('editor/dashboard', [
            'articles'        => $articles,
            'flash'           => $flash,
            'pendingArticles' => $pendingArticles,
            'pendingComments' => $pendingComments,
        ], 'Editor Dashboard - ' . SITE_NAME);
    }

    public function approve(string $id): void {
        $this->requireRole('editor');

        $articleId = (int) $id;
        $this->articleModel->updateStatus($articleId, 'approved');
        $this->setFlash('success', 'Article approved and published.');
        $this->redirect('/editor/dashboard');
    }

    public function reject(string $id): void {
        $this->requireRole('editor');

        $articleId = (int) $id;
        $this->articleModel->updateStatus($articleId, 'rejected');
        $this->setFlash('warning', 'Article rejected.');
        $this->redirect('/editor/dashboard');
    }

    public function delete(string $id): void {
        $this->requireRole('editor');

        $articleId = (int) $id;
        $article   = $this->articleModel->getByIdForEdit($articleId);

        if ($article && $article['image_path']) {
            $filePath = UPLOAD_DIR . $article['image_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $this->articleModel->delete($articleId);
        $this->setFlash('success', 'Article deleted.');
        $this->redirect('/editor/dashboard');
    }

    public function comments(): void {
        $this->requireRole('editor');

        $comments = $this->commentModel->getAll();
        $flash    = $this->getFlash();

        $this->view('editor/comments', [
            'comments' => $comments,
            'flash'    => $flash,
        ], 'Comment Moderation - ' . SITE_NAME);
    }

    public function approveComment(string $id): void {
        $this->requireRole('editor');

        $this->commentModel->approve((int) $id);
        $this->setFlash('success', 'Comment approved.');
        $this->redirect('/editor/comments');
    }

    public function deleteComment(string $id): void {
        $this->requireRole('editor');

        $this->commentModel->delete((int) $id);
        $this->setFlash('success', 'Comment deleted.');
        $this->redirect('/editor/comments');
    }
}
