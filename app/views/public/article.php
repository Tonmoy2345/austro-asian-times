<div class="article-detail">

    <header class="article-header">
        <h1 class="article-title"><?= e($article['title']) ?></h1>
        <div class="article-meta">
            <span class="meta-author">By <strong><?= e($article['author_name']) ?></strong></span>
            <span class="meta-sep">&bull;</span>
            <span class="meta-time" title="<?= e($article['created_at']) ?>">
                Published <?= e(timeAgo($article['created_at'])) ?>
                <em>(<?= date('d M Y, g:i a', strtotime($article['created_at'])) ?>)</em>
            </span>
            <?php if ($article['updated_at'] !== $article['created_at']): ?>
                <span class="meta-sep">&bull;</span>
                <span class="meta-updated" title="<?= e($article['updated_at']) ?>">
                    Last updated <?= e(timeAgo($article['updated_at'])) ?>
                    <em>(<?= date('d M Y, g:i a', strtotime($article['updated_at'])) ?>)</em>
                </span>
            <?php endif; ?>
        </div>

        <?php if (!empty($tags)): ?>
            <div class="article-tags">
                <?php foreach ($tags as $tag): ?>
                    <a href="<?= BASE_URL ?>/tag/<?= urlencode($tag['name']) ?>" class="tag-badge">
                        <?= e($tag['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </header>

    <?php if ($article['image_path']): ?>
        <div class="article-image">
            <img
                src="<?= BASE_URL ?>/public/uploads/<?= e($article['image_path']) ?>"
                alt="<?= e($article['title']) ?>"
            >
        </div>
    <?php endif; ?>

    <div class="article-body">
        <?php
        // split on blank lines for paragraph display
        $paragraphs = array_filter(explode("\n\n", $article['content']));
        foreach ($paragraphs as $para): ?>
            <p><?= nl2br(e(trim($para))) ?></p>
        <?php endforeach; ?>
    </div>

    <div class="article-back">
        <a href="<?= BASE_URL ?>/" class="btn btn-outline">&larr; Back to Home</a>
    </div>

</div>

<section class="comments-section">
    <h2 class="comments-title">Comments (<?= count($comments) ?>)</h2>

    <?php if (empty($comments)): ?>
        <p class="no-comments">No comments yet. Be the first to comment.</p>
    <?php else: ?>
        <div class="comments-list">
            <?php foreach ($comments as $comment): ?>
                <div class="comment-item">
                    <div class="comment-header">
                        <strong class="comment-author"><?= e($comment['author_name']) ?></strong>
                        <span class="comment-date"><?= e(timeAgo($comment['created_at'])) ?></span>
                    </div>
                    <p class="comment-content"><?= nl2br(e($comment['content'])) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="comment-form-wrap">
        <h3>Leave a Comment</h3>
        <p class="comment-note">Comments are moderated before they appear publicly.</p>

        <form method="POST" action="<?= BASE_URL ?>/comment/<?= (int) $article['id'] ?>" novalidate>
            <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">

            <div class="form-group">
                <label for="author_name">Your Name</label>
                <input
                    type="text"
                    id="author_name"
                    name="author_name"
                    class="form-control"
                    placeholder="Enter your name"
                    maxlength="100"
                    required
                >
            </div>

            <div class="form-group">
                <label for="comment_content">Comment</label>
                <textarea
                    id="comment_content"
                    name="content"
                    class="form-control"
                    rows="4"
                    placeholder="Write your comment here..."
                    required
                ></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit Comment</button>
        </form>
    </div>
</section>
