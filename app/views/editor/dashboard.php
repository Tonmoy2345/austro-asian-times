<div class="dashboard-header">
    <h1>Editor Dashboard</h1>
    <div class="dashboard-badges">
        <?php if ($pendingArticles > 0): ?>
            <span class="badge badge-warning"><?= (int) $pendingArticles ?> pending article<?= $pendingArticles > 1 ? 's' : '' ?></span>
        <?php endif; ?>
        <?php if ($pendingComments > 0): ?>
            <a href="<?= BASE_URL ?>/editor/comments">
                <span class="badge badge-info"><?= (int) $pendingComments ?> pending comment<?= $pendingComments > 1 ? 's' : '' ?></span>
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="editor-nav-links">
    <a href="<?= BASE_URL ?>/editor/comments" class="btn btn-outline">Manage Comments</a>
    <a href="<?= BASE_URL ?>/" class="btn btn-outline" target="_blank">View Live Site &rarr;</a>
</div>

<?php if (empty($articles)): ?>
    <div class="empty-state">
        <p>No articles in the system yet.</p>
    </div>
<?php else: ?>
    <div class="filter-tabs">
        <button class="tab-btn tab-active" data-filter="all">All</button>
        <button class="tab-btn" data-filter="pending">Pending</button>
        <button class="tab-btn" data-filter="approved">Approved</button>
        <button class="tab-btn" data-filter="rejected">Rejected</button>
    </div>

    <div class="table-responsive">
        <table class="data-table" id="articles-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>
                    <tr class="article-row" data-status="<?= e($article['status']) ?>">
                        <td class="td-title">
                            <?php if ($article['status'] === 'approved'): ?>
                                <a href="<?= BASE_URL ?>/article/<?= (int) $article['id'] ?>">
                                    <?= e($article['title']) ?>
                                </a>
                            <?php else: ?>
                                <?= e($article['title']) ?>
                            <?php endif; ?>
                        </td>
                        <td><?= e($article['author_name']) ?></td>
                        <td>
                            <span class="status-badge status-<?= e($article['status']) ?>">
                                <?= ucfirst(e($article['status'])) ?>
                            </span>
                        </td>
                        <td><?= date('d M Y', strtotime($article['updated_at'])) ?></td>
                        <td class="td-actions">
                            <?php if ($article['status'] === 'pending'): ?>
                                <a href="<?= BASE_URL ?>/editor/approve/<?= (int) $article['id'] ?>"
                                   class="btn btn-sm btn-success"
                                   onclick="return confirm('Approve this article and publish it?')">
                                   Approve
                                </a>
                                <a href="<?= BASE_URL ?>/editor/reject/<?= (int) $article['id'] ?>"
                                   class="btn btn-sm btn-warning"
                                   onclick="return confirm('Reject this article?')">
                                   Reject
                                </a>
                            <?php elseif ($article['status'] === 'approved'): ?>
                                <a href="<?= BASE_URL ?>/editor/reject/<?= (int) $article['id'] ?>"
                                   class="btn btn-sm btn-warning"
                                   onclick="return confirm('Unpublish this article?')">
                                   Unpublish
                                </a>
                            <?php elseif ($article['status'] === 'rejected'): ?>
                                <a href="<?= BASE_URL ?>/editor/approve/<?= (int) $article['id'] ?>"
                                   class="btn btn-sm btn-success"
                                   onclick="return confirm('Approve and publish this article?')">
                                   Approve
                                </a>
                            <?php endif; ?>
                            <a href="<?= BASE_URL ?>/journalist/edit/<?= (int) $article['id'] ?>"
                               class="btn btn-sm btn-outline">Edit</a>
                            <a href="<?= BASE_URL ?>/editor/delete/<?= (int) $article['id'] ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Permanently delete this article? This cannot be undone.')">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
