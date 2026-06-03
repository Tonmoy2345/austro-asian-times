<?php
// tag model - keywords linked to articles (many-to-many)

class Tag extends Model {

    public function getAll(): array {
        $stmt = $this->db->query("SELECT id, name FROM tags ORDER BY name");
        return $stmt->fetchAll();
    }

    public function getByArticle(int $articleId): array {
        $stmt = $this->db->prepare(
            "SELECT t.id, t.name
             FROM tags t
             JOIN article_tags at ON t.id = at.tag_id
             WHERE at.article_id = :article_id
             ORDER BY t.name"
        );
        $stmt->execute([':article_id' => $articleId]);
        return $stmt->fetchAll();
    }

    // get existing tag or insert new one
    public function findOrCreate(string $name): int {
        $name = strtolower(trim($name));

        $stmt = $this->db->prepare("SELECT id FROM tags WHERE name = :name LIMIT 1");
        $stmt->execute([':name' => $name]);
        $row = $stmt->fetch();

        if ($row) {
            return (int) $row['id'];
        }

        $insert = $this->db->prepare("INSERT INTO tags (name) VALUES (:name)");
        $insert->execute([':name' => $name]);
        return (int) $this->db->lastInsertId();
    }

    public function attachToArticle(int $articleId, int $tagId): void {
        $stmt = $this->db->prepare(
            "INSERT IGNORE INTO article_tags (article_id, tag_id) VALUES (:article_id, :tag_id)"
        );
        $stmt->execute([':article_id' => $articleId, ':tag_id' => $tagId]);
    }

    public function detachFromArticle(int $articleId): void {
        $stmt = $this->db->prepare("DELETE FROM article_tags WHERE article_id = :article_id");
        $stmt->execute([':article_id' => $articleId]);
    }

    // parse comma-separated keywords and save
    public function saveTagsForArticle(int $articleId, string $tagString): void {
        $this->detachFromArticle($articleId);

        if (trim($tagString) === '') {
            return;
        }

        $tags = array_filter(array_map('trim', explode(',', $tagString)));
        foreach ($tags as $tagName) {
            if ($tagName !== '') {
                $tagId = $this->findOrCreate($tagName);
                $this->attachToArticle($articleId, $tagId);
            }
        }
    }

    // for pre-filling the edit form
    public function getTagStringForArticle(int $articleId): string {
        $tags = $this->getByArticle($articleId);
        return implode(', ', array_column($tags, 'name'));
    }

    // only tags that have at least one published article
    public function getTagsWithArticles(): array {
        $stmt = $this->db->query(
            "SELECT DISTINCT t.id, t.name
             FROM tags t
             JOIN article_tags at ON t.id = at.tag_id
             JOIN articles a      ON at.article_id = a.id
             WHERE a.status = 'approved'
             ORDER BY t.name"
        );
        return $stmt->fetchAll();
    }
}
