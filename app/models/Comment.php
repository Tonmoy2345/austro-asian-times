<?php
// comment model - reader comments with moderation

class Comment extends Model {

    public function getApprovedByArticle(int $articleId): array {
        $stmt = $this->db->prepare(
            "SELECT id, author_name, content, created_at
             FROM comments
             WHERE article_id = :article_id AND status = 'approved'
             ORDER BY created_at ASC"
        );
        $stmt->execute([':article_id' => $articleId]);
        return $stmt->fetchAll();
    }

    public function getAllPending(): array {
        $stmt = $this->db->query(
            "SELECT c.id, c.author_name, c.content, c.created_at, c.status,
                    a.id AS article_id, a.title AS article_title
             FROM comments c
             JOIN articles a ON c.article_id = a.id
             WHERE c.status = 'pending'
             ORDER BY c.created_at ASC"
        );
        return $stmt->fetchAll();
    }

    public function getAll(): array {
        $stmt = $this->db->query(
            "SELECT c.id, c.author_name, c.content, c.created_at, c.status,
                    a.id AS article_id, a.title AS article_title
             FROM comments c
             JOIN articles a ON c.article_id = a.id
             ORDER BY c.status ASC, c.created_at DESC"
        );
        return $stmt->fetchAll();
    }

    // new comments start as pending
    public function create(int $articleId, string $authorName, string $content): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO comments (article_id, author_name, content, status)
             VALUES (:article_id, :author_name, :content, 'pending')"
        );
        return $stmt->execute([
            ':article_id'  => $articleId,
            ':author_name' => $authorName,
            ':content'     => $content,
        ]);
    }

    public function approve(int $id): bool {
        $stmt = $this->db->prepare(
            "UPDATE comments SET status = 'approved' WHERE id = :id"
        );
        return $stmt->execute([':id' => $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM comments WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
