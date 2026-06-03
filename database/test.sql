-- crud tests for all tables
-- run after create.sql (load.sql not required)

USE austro_asian_times;

-- users table
INSERT INTO users (username, email, password, role)
VALUES ('test_journalist', 'testj@test.com', '$2y$10$testhashedpasswordvalue123', 'journalist');

SELECT * FROM users WHERE username = 'test_journalist';

SELECT id, username, email, role, created_at FROM users WHERE role = 'journalist';

UPDATE users SET email = 'updated_testj@test.com' WHERE username = 'test_journalist';

SELECT id, username, email FROM users WHERE username = 'test_journalist';

-- articles table
SET @test_user_id = (SELECT id FROM users WHERE username = 'test_journalist');

INSERT INTO articles (title, content, author_id, status)
VALUES ('Test Article Title', 'This is the test article content body.', @test_user_id, 'pending');

SELECT * FROM articles WHERE title = 'Test Article Title';

SET @test_article_id = (SELECT id FROM articles WHERE title = 'Test Article Title');

SELECT a.id, a.title, a.status, u.username as author
FROM articles a
JOIN users u ON a.author_id = u.id
WHERE a.status = 'pending';

UPDATE articles SET status = 'approved' WHERE id = @test_article_id;

SELECT id, title, status FROM articles WHERE id = @test_article_id;

SELECT a.id, a.title, a.status, a.updated_at, u.username as author
FROM articles a
JOIN users u ON a.author_id = u.id
WHERE a.status = 'approved'
ORDER BY a.updated_at DESC
LIMIT 5;

-- tags table
INSERT INTO tags (name) VALUES ('TestRegion');

SELECT * FROM tags WHERE name = 'TestRegion';

SET @test_tag_id = (SELECT id FROM tags WHERE name = 'TestRegion');

UPDATE tags SET name = 'TestRegionUpdated' WHERE id = @test_tag_id;

SELECT * FROM tags WHERE id = @test_tag_id;

-- article_tags junction
INSERT INTO article_tags (article_id, tag_id) VALUES (@test_article_id, @test_tag_id);

SELECT t.name
FROM tags t
JOIN article_tags at ON t.id = at.tag_id
WHERE at.article_id = @test_article_id;

SELECT a.title
FROM articles a
JOIN article_tags at ON a.id = at.article_id
WHERE at.tag_id = @test_tag_id;

DELETE FROM article_tags WHERE article_id = @test_article_id AND tag_id = @test_tag_id;

-- comments table
INSERT INTO comments (article_id, author_name, content, status)
VALUES (@test_article_id, 'Test Reader', 'This is a test comment content.', 'pending');

SELECT * FROM comments WHERE status = 'pending';

SET @test_comment_id = (SELECT id FROM comments WHERE author_name = 'Test Reader' AND article_id = @test_article_id);

UPDATE comments SET status = 'approved' WHERE id = @test_comment_id;

SELECT id, author_name, status FROM comments WHERE id = @test_comment_id;

SELECT id, author_name, content, created_at
FROM comments
WHERE article_id = @test_article_id AND status = 'approved';

-- cleanup test rows
DELETE FROM comments    WHERE id = @test_comment_id;
DELETE FROM article_tags WHERE article_id = @test_article_id;
DELETE FROM articles    WHERE id = @test_article_id;
DELETE FROM tags        WHERE id = @test_tag_id;
DELETE FROM users       WHERE username = 'test_journalist';

SELECT 'Test cleanup complete' AS result;
SELECT COUNT(*) AS remaining_test_users    FROM users    WHERE username = 'test_journalist';
SELECT COUNT(*) AS remaining_test_articles FROM articles WHERE title = 'Test Article Title';
