<div class="form-page">
    <div class="form-page-header">
        <h1>Write New Article</h1>
        <a href="<?= BASE_URL ?>/journalist/dashboard" class="btn btn-outline">&larr; Back to My Articles</a>
    </div>

    <form method="POST" action="<?= BASE_URL ?>/journalist/create" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">

        <div class="form-group">
            <label for="title">Article Title <span class="required">*</span></label>
            <input
                type="text"
                id="title"
                name="title"
                class="form-control"
                placeholder="Enter a clear, descriptive title"
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
                placeholder="Write your article here. Separate paragraphs with a blank line."
                rows="18"
                required
            ></textarea>
        </div>

        <div class="form-group">
            <label for="keywords">Keywords / Tags</label>
            <input
                type="text"
                id="keywords"
                name="keywords"
                class="form-control"
                placeholder="e.g. Darwin, Indonesia, Economy, Tourism"
            >
            <small class="form-hint">
                Separate keywords with commas. Use country names, city names, topics etc.
                These help readers find your article.
            </small>
        </div>

        <div class="form-group">
            <label for="image">Article Image (optional)</label>
            <input
                type="file"
                id="image"
                name="image"
                class="form-control-file"
                accept="image/jpeg,image/png,image/gif,image/webp"
            >
            <small class="form-hint">JPG, PNG, GIF or WEBP. Maximum size 2MB.</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Submit Article</button>
            <a href="<?= BASE_URL ?>/journalist/dashboard" class="btn btn-outline">Cancel</a>
        </div>

        <p class="submit-note">
            Your article will be reviewed by the editor before it appears on the site.
        </p>
    </form>
</div>
