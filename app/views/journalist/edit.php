<div class="form-page">
    <div class="form-page-header">
        <h1>Edit Article</h1>
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'editor'): ?>
            <a href="<?= BASE_URL ?>/editor/dashboard" class="btn btn-outline">&larr; Back to Dashboard</a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>/journalist/dashboard" class="btn btn-outline">&larr; Back to My Articles</a>
        <?php endif; ?>
    </div>

    <?php if ($article['status'] === 'rejected'): ?>
        <div class="alert alert-warning">
            This article was rejected by the editor. You can revise and resubmit it.
        </div>
    <?php endif; ?>

    <form method="POST"
          action="<?= BASE_URL ?>/journalist/edit/<?= (int) $article['id'] ?>"
          enctype="multipart/form-data"
          novalidate>
        <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">

        <div class="form-group">
            <label for="title">Article Title <span class="required">*</span></label>
            <input
                type="text"
                id="title"
                name="title"
                class="form-control"
                value="<?= e($article['title']) ?>"
                maxlength="255"
                required
            >
        </div>

        <div class="form-group">
            <label for="content">Article Content <span class="required">*</span></label>
            <textarea
                id="content"
                name="content"
                class="form-control article-textarea"
                rows="18"
                required
            ><?= e($article['content']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="keywords">Keywords / Tags</label>
            <input
                type="text"
                id="keywords"
                name="keywords"
                class="form-control"
                value="<?= e($keywords) ?>"
                placeholder="e.g. Darwin, Indonesia, Economy, Tourism"
            >
            <small class="form-hint">Separate keywords with commas.</small>
        </div>

        <div class="form-group">
            <label for="image">Replace Image (optional)</label>
            <?php if ($article['image_path']): ?>
                <div class="current-image">
                    <p>Current image:</p>
                    <img src="<?= BASE_URL ?>/public/uploads/<?= e($article['image_path']) ?>"
                         alt="Current article image" class="preview-image">
                </div>
            <?php endif; ?>
            <input
                type="file"
                id="image"
                name="image"
                class="form-control-file"
                accept="image/jpeg,image/png,image/gif,image/webp"
            >
            <small class="form-hint">
                Upload a new image to replace the current one. JPG, PNG, GIF or WEBP. Maximum 2MB.
                Leave empty to keep existing image.
            </small>
        </div>

        <div class="article-info">
            <small>
                Created: <?= date('d M Y, g:i a', strtotime($article['created_at'])) ?>
                &bull;
                Last updated: <?= date('d M Y, g:i a', strtotime($article['updated_at'])) ?>
            </small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'editor'): ?>
                <a href="<?= BASE_URL ?>/editor/dashboard" class="btn btn-outline">Cancel</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/journalist/dashboard" class="btn btn-outline">Cancel</a>
            <?php endif; ?>
        </div>

        <p class="submit-note">Saving changes will reset the article status to pending for editor review.</p>
    </form>
</div>
