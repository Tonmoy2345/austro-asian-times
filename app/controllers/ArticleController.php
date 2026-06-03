<?php
// public pages, journalist article management

class ArticleController extends Controller {

    private Article $articleModel;
    private Tag     $tagModel;

    public function __construct() {
        $this->articleModel = new Article();
        $this->tagModel     = new Tag();
    }

    public function index(): void {
        $articles  = $this->articleModel->getTopFive();
        $tags      = $this->tagModel->getTagsWithArticles();
        $flash     = $this->getFlash();

        foreach ($articles as &$article) {
            $article['tags'] = $this->tagModel->getByArticle($article['id']);
        }
        unset($article);

        $this->view('public/index', [
            'articles' => $articles,
            'tags'     => $tags,
            'flash'    => $flash,
        ], SITE_NAME . ' - News for Northern Australia and Southeast Asia');
    }

    public function show(string $id): void {
        $articleId = (int) $id;
        $article   = $this->articleModel->getById($articleId);

        if (!$article || $article['status'] !== 'approved') {
            http_response_code(404);
            $this->view('public/404', [], 'Article Not Found');
            return;
        }

        $commentModel = new Comment();
        $tags         = $this->tagModel->getByArticle($articleId);
        $comments     = $commentModel->getApprovedByArticle($articleId);
        $allTags      = $this->tagModel->getTagsWithArticles();
        $flash        = $this->getFlash();
        $csrf         = $this->generateCsrf();

        $this->view('public/article', [
            'article'  => $article,
            'tags'     => $tags,
            'comments' => $comments,
            'allTags'  => $allTags,
            'flash'    => $flash,
            'csrf'     => $csrf,
        ], e($article['title']) . ' - ' . SITE_NAME);
    }

    public function byTag(string $tagName): void {
        $tagName  = urldecode($tagName);
        $articles = $this->articleModel->getByTag($tagName);
        $allTags  = $this->tagModel->getTagsWithArticles();
        $flash    = $this->getFlash();

        foreach ($articles as &$article) {
            $article['tags'] = $this->tagModel->getByArticle($article['id']);
        }
        unset($article);

        $this->view('public/tag', [
            'articles' => $articles,
            'tagName'  => $tagName,
            'allTags'  => $allTags,
            'flash'    => $flash,
        ], 'Tag: ' . e($tagName) . ' - ' . SITE_NAME);
    }

    public function journalistDashboard(): void {
        $this->requireRole('journalist');

        $articles = $this->articleModel->getByAuthor((int) $_SESSION['user_id']);
        $flash    = $this->getFlash();

        $this->view('journalist/dashboard', [
            'articles' => $articles,
            'flash'    => $flash,
        ], 'My Articles - ' . SITE_NAME);
    }

    public function showCreate(): void {
        $this->requireRole('journalist');

        $flash = $this->getFlash();
        $csrf  = $this->generateCsrf();

        $this->view('journalist/create', [
            'flash' => $flash,
            'csrf'  => $csrf,
        ], 'New Article - ' . SITE_NAME);
    }

    public function create(): void {
        $this->requireRole('journalist');
        $this->validateCsrf();

        $title    = trim($_POST['title']    ?? '');
        $content  = trim($_POST['content']  ?? '');
        $keywords = trim($_POST['keywords'] ?? '');

        if (empty($title) || empty($content)) {
            $this->setFlash('danger', 'Title and content are required.');
            $this->redirect('/journalist/create');
        }

        $imagePath = $this->handleImageUpload('image');
        $articleId = $this->articleModel->create($title, $content, (int) $_SESSION['user_id'], $imagePath);

        if (!empty($keywords)) {
            $this->tagModel->saveTagsForArticle($articleId, $keywords);
        }

        $this->setFlash('success', 'Article submitted successfully. It will appear once the editor approves it.');
        $this->redirect('/journalist/dashboard');
    }

    public function showEdit(string $id): void {
        $this->requireAuth();

        $articleId = (int) $id;
        $article   = $this->articleModel->getByIdForEdit($articleId);

        if (!$article) {
            $this->setFlash('danger', 'Article not found.');
            $this->redirect('/journalist/dashboard');
        }

        // journalists only edit their own stuff
        if ($_SESSION['user_role'] === 'journalist' && $article['author_id'] != $_SESSION['user_id']) {
            $this->setFlash('danger', 'You can only edit your own articles.');
            $this->redirect('/journalist/dashboard');
        }

        $keywords = $this->tagModel->getTagStringForArticle($articleId);
        $flash    = $this->getFlash();
        $csrf     = $this->generateCsrf();

        $this->view('journalist/edit', [
            'article'  => $article,
            'keywords' => $keywords,
            'flash'    => $flash,
            'csrf'     => $csrf,
        ], 'Edit Article - ' . SITE_NAME);
    }

    public function update(string $id): void {
        $this->requireAuth();
        $this->validateCsrf();

        $articleId = (int) $id;
        $article   = $this->articleModel->getByIdForEdit($articleId);

        if (!$article) {
            $this->setFlash('danger', 'Article not found.');
            $this->redirect('/journalist/dashboard');
        }

        if ($_SESSION['user_role'] === 'journalist' && $article['author_id'] != $_SESSION['user_id']) {
            $this->setFlash('danger', 'You can only edit your own articles.');
            $this->redirect('/journalist/dashboard');
        }

        $title    = trim($_POST['title']    ?? '');
        $content  = trim($_POST['content']  ?? '');
        $keywords = trim($_POST['keywords'] ?? '');

        if (empty($title) || empty($content)) {
            $this->setFlash('danger', 'Title and content are required.');
            $this->redirect('/journalist/edit/' . $articleId);
        }

        $imagePath = $this->handleImageUpload('image');

        $this->articleModel->update($articleId, $title, $content, $imagePath);
        $this->tagModel->saveTagsForArticle($articleId, $keywords);

        $this->setFlash('success', 'Article updated. It has been reset to pending for editor review.');

        if ($_SESSION['user_role'] === 'editor') {
            $this->redirect('/editor/dashboard');
        } else {
            $this->redirect('/journalist/dashboard');
        }
    }

    public function delete(string $id): void {
        $this->requireAuth();

        $articleId = (int) $id;
        $article   = $this->articleModel->getByIdForEdit($articleId);

        if (!$article) {
            $this->setFlash('danger', 'Article not found.');
            $this->redirect('/journalist/dashboard');
        }

        if ($_SESSION['user_role'] === 'journalist' && $article['author_id'] != $_SESSION['user_id']) {
            $this->setFlash('danger', 'You can only delete your own articles.');
            $this->redirect('/journalist/dashboard');
        }

        if ($article['image_path']) {
            $filePath = UPLOAD_DIR . $article['image_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $this->articleModel->delete($articleId);
        $this->setFlash('success', 'Article deleted.');

        if ($_SESSION['user_role'] === 'editor') {
            $this->redirect('/editor/dashboard');
        } else {
            $this->redirect('/journalist/dashboard');
        }
    }
}
