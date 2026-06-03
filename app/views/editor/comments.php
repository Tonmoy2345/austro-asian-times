<div class="dashboard-header">
    <h1>Comment Moderation</h1>
    <a href="<?= BASE_URL ?>/editor/dashboard" class="btn btn-outline">&larr; Back to Dashboard</a>
</div>

<?php if (empty($comments)): ?>
    <div class="empty-state">
        <p>No comments in the system yet.</p>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Author</th>
                    <th>Comment</th>
                    <th>Article</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comments as $comment): ?>
                    <tr class="<?= $comment['status'] === 'pending' ? 'row-pending' : '' ?>">
                        <td><strong><?= e($comment['author_name']) ?></strong></td>
                        <td class="td-comment">
                            <?= e(mb_substr($comment['content'], 0, 120)) ?>
                            <?= strlen($comment['content']) > 120 ? '...' : '' ?>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>/article/<?= (int) $comment['article_id'] ?>">
                                <?= e(mb_substr($comment['article_title'], 0, 40)) ?>...
                            </a>
                        </td>
                        <td>
                            <span class="status-badge status-<?= e($comment['status']) ?>">
                                <?= ucfirst(e($comment['status'])) ?>
                            </span>
                        </td>
                        <td><?= date('d M Y', strtotime($comment['created_at'])) ?></td>
                        <td class="td-actions">
                            <?php if ($comment['status'] === 'pending'): ?>
                                <a href="<?= BASE_URL ?>/editor/comments/approve/<?= (int) $comment['id'] ?>"
                                   class="btn btn-sm btn-success"
                                   onclick="return confirm('Approve this comment?')">
                                   Approve
                                </a>
                            <?php endif; ?>
                            <a href="<?= BASE_URL ?>/editor/comments/delete/<?= (int) $comment['id'] ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Delete this comment permanently?')">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
