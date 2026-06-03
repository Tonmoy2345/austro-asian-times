-- create database and tables for austro-asian-times
-- run this first in phpmyadmin or mysql cli

CREATE DATABASE IF NOT EXISTS austro_asian_times
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE austro_asian_times;

-- drop in order because of foreign keys
DROP TABLE IF EXISTS article_tags;
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS tags;
DROP TABLE IF EXISTS articles;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id       INT          AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50)  NOT NULL UNIQUE,
    email    VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role     ENUM('journalist','editor') NOT NULL DEFAULT 'journalist',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE articles (
    id         INT          AUTO_INCREMENT PRIMARY KEY,
    title      VARCHAR(255) NOT NULL,
    content    TEXT         NOT NULL,
    author_id  INT          NOT NULL,
    status     ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    image_path VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- keyword tags (folksonomy)
CREATE TABLE tags (
    id   INT         AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- links articles to tags (many-to-many)
CREATE TABLE article_tags (
    article_id INT NOT NULL,
    tag_id     INT NOT NULL,
    PRIMARY KEY (article_id, tag_id),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id)     REFERENCES tags(id)     ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE comments (
    id          INT          AUTO_INCREMENT PRIMARY KEY,
    article_id  INT          NOT NULL,
    author_name VARCHAR(100) NOT NULL,
    content     TEXT         NOT NULL,
    status      ENUM('pending','approved') NOT NULL DEFAULT 'pending',
    created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
