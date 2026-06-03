<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) : e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <link rel="alternate" type="application/rss+xml"  title="<?= e(SITE_NAME) ?> RSS Feed"  href="<?= BASE_URL ?>/feed/rss">
    <link rel="alternate" type="application/atom+xml" title="<?= e(SITE_NAME) ?> Atom Feed" href="<?= BASE_URL ?>/feed/atom">
</head>
<body>

<header class="site-header">
    <div class="container">
        <div class="header-top">
            <a href="<?= BASE_URL ?>/" class="site-title">
                <span class="site-title-main"><?= e(SITE_NAME) ?></span>
                <span class="site-title-sub">News for Northern Australia &amp; Southeast Asia</span>
            </a>
            <nav class="header-nav">
                <?php if (!empty($_SESSION['user_id'])): ?>
                    <span class="nav-user">
                        Logged in as <strong><?= e($_SESSION['user_username']) ?></strong>
                        (<?= e($_SESSION['user_role']) ?>)
                    </span>
                    <?php if ($_SESSION['user_role'] === 'editor'): ?>
                        <a href="<?= BASE_URL ?>/editor/dashboard" class="btn btn-outline">Dashboard</a>
                        <a href="<?= BASE_URL ?>/editor/comments"  class="btn btn-outline">Comments</a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/journalist/dashboard" class="btn btn-outline">My Articles</a>
                        <a href="<?= BASE_URL ?>/journalist/create"    class="btn btn-outline">New Article</a>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>/logout" class="btn btn-danger">Logout</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login" class="btn btn-primary">Login</a>
                <?php endif; ?>
            </nav>
        </div>

        <?php if (!empty($tags) || !empty($allTags)): ?>
            <?php $navTags = $tags ?? $allTags ?? []; ?>
            <?php if (!empty($navTags)): ?>
            <nav class="tag-nav">
                <span class="tag-nav-label">Browse by topic:</span>
                <?php foreach ($navTags as $tag): ?>
                    <a href="<?= BASE_URL ?>/tag/<?= urlencode($tag['name']) ?>" class="tag-link">
                        <?= e($tag['name']) ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</header>

<main class="main-content">
    <div class="container">

        <?php if (!empty($flash)): ?>
            <div class="alert alert-<?= e($flash['type']) ?>">
                <?= e($flash['message']) ?>
                <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
            </div>
        <?php endif; ?>
