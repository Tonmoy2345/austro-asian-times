<?php
// article model - crud and listing queries

class Article extends Model {

    // top 5 approved articles for homepage
    public function getTopFive(): array {
        $stmt = $this->db->query(
            "SELECT a.id, a.title, a.content, a.image_path,
                    a.created_at, a.updated_at, u.username AS author_name
             FROM articles a
             JOIN users u ON a.author_id = u.id
             WHERE a.status = 'approved'
             ORDER BY a.updated_at DESC
             LIMIT 5"
        );
        return $stmt->fetchAll();
    }

    public function getAllApproved(): array {
        $stmt = $this->db->query(
            "SELECT a.id, a.title, a.content, a.image_path,
                    a.created_at, a.updated_at, u.username AS author_name
             FROM articles a
             JOIN users u ON a.author_id = u.id
             WHERE a.status = 'approved'
             ORDER BY a.updated_at DESC"
        );
        return $stmt->fetchAll();
    }

    // public view - any status returned, caller checks approved
    public function getById(int $id): array|false {
        $stmt = $this->db->prepare(
            "SELECT a.id, a.title, a.content, a.image_path, a.status,
                    a.created_at, a.updated_at, u.username AS author_name
             FROM articles a
             JOIN users u ON a.author_id = u.id
             WHERE a.id = :id
             LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // for edit page - includes author_id
    public function getByIdForEdit(int $id): array|false {
        $stmt = $this->db->prepare(
            "SELECT a.id, a.title, a.content, a.image_path, a.status,
                    a.author_id, a.created_at, a.updated_at,
                    u.username AS author_name
             FROM articles a
             JOIN users u ON a.author_id = u.id
             WHERE a.id = :id
             LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getByAuthor(int $authorId): array {
        $stmt = $this->db->prepare(
            "SELECT id, title, status, created_at, updated_at
             FROM articles
             WHERE author_id = :author_id
             ORDER BY updated_at DESC"
        );
        $stmt->execute([':author_id' => $authorId]);
        return $stmt->fetchAll();
    }

    // editor sees all statuses, pending first
    public function getAllForEditor(): array {
        $stmt = $this->db->query(
            "SELECT a.id, a.title, a.status, a.created_at, a.updated_at,
                    u.username AS author_name
             FROM articles a
             JOIN users u ON a.author_id = u.id
             ORDER BY
               FIELD(a.status, 'pending', 'approved', 'rejected'),
               a.updated_at DESC"
        );
        return $stmt->fetchAll();
    }

    public function getByTag(string $tagName): array {
        $stmt = $this->db->prepare(
            "SELECT a.id, a.title, a.content, a.image_path,
                    a.created_at, a.updated_at, u.username AS author_name
             FROM articles a
             JOIN users u        ON a.author_id = u.id
             JOIN article_tags at ON a.id = at.article_id
             JOIN tags t          ON at.tag_id = t.id
             WHERE a.status = 'approved'
               AND t.name = :tag
             ORDER BY a.updated_at DESC"
        );
        $stmt->execute([':tag' => $tagName]);
        return $stmt->fetchAll();
    }

    public function getFeedArticles(int $limit = 20): array {
        $stmt = $this->db->prepare(
            "SELECT a.id, a.title, a.content, a.created_at, a.updated_at,
                    u.username AS author_name
             FROM articles a
             JOIN users u ON a.author_id = u.id
             WHERE a.status = 'approved'
             ORDER BY a.updated_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create(string $title, string $content, int $authorId, ?string $imagePath = null): int {
        $stmt = $this->db->prepare(
            "INSERT INTO articles (title, content, author_id, status, image_path)
             VALUES (:title, :content, :author_id, 'pending', :image_path)"
        );
        $stmt->execute([
            ':title'      => $title,
            ':content'    => $content,
            ':author_id'  => $authorId,
            ':image_path' => $imagePath,
        ]);
        return (int) $this->db->lastInsertId();
    }

    // editing sends article back to pending for review
    public function update(int $id, string $title, string $content, ?string $imagePath = null): bool {
        if ($imagePath !== null) {
            $stmt = $this->db->prepare(
                "UPDATE articles
                 SET title = :title, content = :content, image_path = :image_path, status = 'pending'
                 WHERE id = :id"
            );
            return $stmt->execute([
                ':title'      => $title,
                ':content'    => $content,
                ':image_path' => $imagePath,
                ':id'         => $id,
            ]);
        } else {
            $stmt = $this->db->prepare(
                "UPDATE articles
                 SET title = :title, content = :content, status = 'pending'
                 WHERE id = :id"
            );
            return $stmt->execute([
                ':title'   => $title,
                ':content' => $content,
                ':id'      => $id,
            ]);
        }
    }

    public function updateStatus(int $id, string $status): bool {
        $stmt = $this->db->prepare(
            "UPDATE articles SET status = :status WHERE id = :id"
        );
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM articles WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
