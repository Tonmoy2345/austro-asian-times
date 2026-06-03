<div class="page-hero">
    <h1>Articles tagged: <span class="tag-highlight"><?= e($tagName) ?></span></h1>
</div>

<?php if (empty($articles)): ?>
    <div class="empty-state">
        <p>No articles found for this tag.</p>
        <a href="<?= BASE_URL ?>/" class="btn btn-outline">Back to Home</a>
    </div>
<?php else: ?>
    <p class="result-count"><?= count($articles) ?> article<?= count($articles) !== 1 ? 's' : '' ?> found</p>

    <div class="articles-grid">
        <?php foreach ($articles as $article): ?>
            <article class="article-card">
                <?php if ($article['image_path']): ?>
                    <div class="article-card-image">
                        <a href="<?= BASE_URL ?>/article/<?= (int) $article['id'] ?>">
                            <img
                                src="<?= BASE_URL ?>/public/uploads/<?= e($article['image_path']) ?>"
                                alt="<?= e($article['title']) ?>"
                                loading="lazy"
                            >
                        </a>
                    </div>
                <?php endif; ?>

                <div class="article-card-body">
                    <h2 class="article-card-title">
                        <a href="<?= BASE_URL ?>/article/<?= (int) $article['id'] ?>">
                            <?= e($article['title']) ?>
                        </a>
                    </h2>

                    <div class="article-meta">
                        <span class="meta-author">By <?= e($article['author_name']) ?></span>
                        <span class="meta-sep">&bull;</span>
                        <span class="meta-time">
                            <?= e(timeAgo($article['updated_at'])) ?>
                        </span>
                    </div>

                    <p class="article-card-excerpt">
                        <?= e(mb_substr(strip_tags($article['content']), 0, 200)) ?>...
                    </p>

                    <?php if (!empty($article['tags'])): ?>
                        <div class="article-tags">
                            <?php foreach ($article['tags'] as $tag): ?>
                                <a href="<?= BASE_URL ?>/tag/<?= urlencode($tag['name']) ?>"
                                   class="tag-badge <?= $tag['name'] === $tagName ? 'tag-badge-active' : '' ?>">
                                    <?= e($tag['name']) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <a href="<?= BASE_URL ?>/article/<?= (int) $article['id'] ?>" class="read-more-link">
                        Read full article &rarr;
                    </a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="back-home">
    <a href="<?= BASE_URL ?>/" class="btn btn-outline">&larr; Back to Home</a>
</div>
