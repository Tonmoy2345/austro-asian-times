<div class="page-hero">
    <h1>Latest News</h1>
    <p>Covering Northern Australia and Southeast Asia</p>
</div>

<?php if (empty($articles)): ?>
    <div class="empty-state">
        <p>No articles have been published yet. Check back soon.</p>
    </div>
<?php else: ?>
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
                        <span class="meta-time" title="<?= e($article['created_at']) ?>">
                            Created <?= e(timeAgo($article['created_at'])) ?>
                        </span>
                        <?php if ($article['updated_at'] !== $article['created_at']): ?>
                            <span class="meta-sep">&bull;</span>
                            <span class="meta-updated" title="<?= e($article['updated_at']) ?>">
                                Updated <?= e(timeAgo($article['updated_at'])) ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <p class="article-card-excerpt">
                        <?= e(mb_substr(strip_tags($article['content']), 0, 200)) ?>...
                    </p>

                    <?php if (!empty($article['tags'])): ?>
                        <div class="article-tags">
                            <?php foreach ($article['tags'] as $tag): ?>
                                <a href="<?= BASE_URL ?>/tag/<?= urlencode($tag['name']) ?>" class="tag-badge">
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

    <div class="feed-links">
        <p>Subscribe to our news feeds:</p>
        <a href="<?= BASE_URL ?>/feed/rss"  class="btn btn-outline feed-btn">&#x2605; RSS Feed</a>
        <a href="<?= BASE_URL ?>/feed/atom" class="btn btn-outline feed-btn">&#x2605; Atom Feed</a>
    </div>
<?php endif; ?>
