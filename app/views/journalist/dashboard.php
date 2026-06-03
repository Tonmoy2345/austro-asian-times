<div class="dashboard-header">
    <h1>My Articles</h1>
    <a href="<?= BASE_URL ?>/journalist/create" class="btn btn-primary">+ New Article</a>
</div>

<?php if (empty($articles)): ?>
    <div class="empty-state">
        <p>You have not submitted any articles yet.</p>
        <a href="<?= BASE_URL ?>/journalist/create" class="btn btn-primary">Write your first article</a>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>
                    <tr>
                        <td class="td-title">
                            <?php if ($article['status'] === 'approved'): ?>
                                <a href="<?= BASE_URL ?>/article/<?= (int) $article['id'] ?>">
                                    <?= e($article['title']) ?>
                                </a>
                            <?php else: ?>
                                <?= e($article['title']) ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-badge status-<?= e($article['status']) ?>">
                                <?= ucfirst(e($article['status'])) ?>
                            </span>
                        </td>
                        <td><?= date('d M Y', strtotime($article['created_at'])) ?></td>
                        <td><?= date('d M Y', strtotime($article['updated_at'])) ?></td>
                        <td class="td-actions">
                            <a href="<?= BASE_URL ?>/journalist/edit/<?= (int) $article['id'] ?>"
                               class="btn btn-sm btn-outline">Edit</a>
                            <a href="<?= BASE_URL ?>/journalist/delete/<?= (int) $article['id'] ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Are you sure you want to delete this article? This cannot be undone.')">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
