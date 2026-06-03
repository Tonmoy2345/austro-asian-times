<?php
// router - reads ?url= from .htaccess and calls the right controller

$rawUrl = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url    = filter_var($rawUrl, FILTER_SANITIZE_URL);

// url parts: controller / action / id
$parts = explode('/', $url);
$seg0  = $parts[0] ?? '';
$seg1  = $parts[1] ?? '';
$seg2  = $parts[2] ?? '';
$seg3  = $parts[3] ?? '';

$method = $_SERVER['REQUEST_METHOD'];

switch ($seg0) {

    // public pages
    case '':
        (new ArticleController())->index();
        break;

    case 'article':
        // e.g. /article/5
        (new ArticleController())->show($seg1);
        break;

    case 'tag':
        // e.g. /tag/Darwin
        (new ArticleController())->byTag($seg1);
        break;

    // login / logout
    case 'login':
        $c = new AuthController();
        if ($method === 'POST') {
            $c->login();
        } else {
            $c->showLogin();
        }
        break;

    case 'logout':
        (new AuthController())->logout();
        break;

    // journalist area
    case 'journalist':
        $c = new ArticleController();
        switch ($seg1) {
            case 'dashboard':
            case '':
                $c->journalistDashboard();
                break;
            case 'create':
                if ($method === 'POST') {
                    $c->create();
                } else {
                    $c->showCreate();
                }
                break;
            case 'edit':
                if ($method === 'POST') {
                    $c->update($seg2);
                } else {
                    $c->showEdit($seg2);
                }
                break;
            case 'delete':
                $c->delete($seg2);
                break;
            default:
                $c->journalistDashboard();
        }
        break;

    // editor area
    case 'editor':
        $c = new EditorController();
        switch ($seg1) {
            case 'dashboard':
            case '':
                $c->dashboard();
                break;
            case 'approve':
                $c->approve($seg2);
                break;
            case 'reject':
                $c->reject($seg2);
                break;
            case 'delete':
                $c->delete($seg2);
                break;
            case 'comments':
                if ($seg2 === 'approve') {
                    $c->approveComment($seg3);
                } elseif ($seg2 === 'delete') {
                    $c->deleteComment($seg3);
                } else {
                    $c->comments();
                }
                break;
            default:
                $c->dashboard();
        }
        break;

    // post a comment on an article
    case 'comment':
        (new CommentController())->store($seg1);
        break;

    // rss and atom feeds
    case 'feed':
        $c = new FeedController();
        if ($seg1 === 'atom') {
            $c->atom();
        } else {
            $c->rss();
        }
        break;

    default:
        http_response_code(404);
        session_start();
        $flash     = [];
        $pageTitle = '404 Not Found';
        $tags      = [];
        require __DIR__ . '/app/views/layouts/header.php';
        require __DIR__ . '/app/views/public/404.php';
        require __DIR__ . '/app/views/layouts/footer.php';
        break;
}
