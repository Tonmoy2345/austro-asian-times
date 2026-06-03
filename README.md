# Austro-Asian Times - News Web Application
### HIT326 Group Project | Group 9 | Charles Darwin University 2026

---

## Group Members & Contributions

| Student | Student ID | Role | Responsibilities |
|---------|------------|------|-----------------|
| Modasser | - | Database Lead | Database schema design, MySQL setup, all table creation, journalist login system with session handling, integration testing, database optimization and documentation |
| Sadik | - | Backend Lead | PHP development environment setup, article submission workflow, article creation and editing forms, editor moderation panel, full approval and rejection workflow, final bug fixes |
| Md Ahnaf Azmain | s374489 | Frontend Lead | Front-end wireframes, front page with top 5 article display, article timestamps, individual article detail pages, mobile responsive CSS layout |
| Masafy Kamran | s377827 | Security Lead | Security requirements research, password hashing with bcrypt, SQL injection protection on all queries, RSS and Atom feed implementation, final security review |
| Tonmoy Acharjee | s375239 | Integration Lead | Project repository setup, keyword tagging system, article browse by tag, reader comment system with moderation, image upload feature, pushed final code to GitHub and report coordination |

---

## Project Overview

This is a database-driven news web application built for Austro-Asian Times. It allows journalists to log in and submit articles, and an editor to moderate and approve content before it goes public. Built with PHP, MySQL, HTML, CSS and JavaScript.

---

## Requirements

- XAMPP (Apache + MySQL + PHP 8.x)
- Web browser (Chrome, Firefox, Edge)
- PHP version: 8.2 or higher
- MySQL / MariaDB

---

## Installation Steps

### Step 1 - Install XAMPP
Download and install XAMPP from https://www.apachefriends.org
Install to the default location: `C:\xampp`

### Step 2 - Start XAMPP
Open XAMPP Control Panel and click **Start** on both:
- Apache
- MySQL

### Step 3 - Copy Project Files
Extract the project zip and copy the `austro-asian-times` folder into:
```
C:\xampp\htdocs\austro-asian-times\
```

Your folder structure should look like:
```
C:\xampp\htdocs\austro-asian-times\
    app\
    database\
    public\
    index.php
    router.php
    .htaccess
```

### Step 4 - Create the Database

1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click the **SQL** tab at the top
3. Open the file `database\create.sql` in Notepad
4. Copy all the text and paste it into the SQL box
5. Click **Go**
6. All tables will be created successfully

### Step 5 - Load Sample Data

1. Stay on the same SQL tab in phpMyAdmin
2. Open the file `database\load.sql` in Notepad
3. Copy all the text and paste it into the SQL box
4. Click **Go**
5. Sample articles, users, tags and comments will be inserted

### Step 6 - Open the Website

Go to: `http://localhost/austro-asian-times`

The front page should load with 6 sample articles.

---

## Test Accounts

| Role       | Username       | Password       |
|------------|----------------|----------------|
| Editor     | editor         | editor123      |
| Journalist | john_reporter  | journalist123  |
| Journalist | sarah_news     | journalist456  |
| Journalist | mike_field     | journalist789  |

---

## Features

- Journalist login with session management
- Article create, edit, delete
- Editor moderation panel (approve / reject / delete articles)
- Front page automatically shows top 5 most recent approved articles
- Article timestamps (created date and last updated date)
- Keyword tagging system (folksonomy) with browse by tag
- RSS Feed: `http://localhost/austro-asian-times/feed/rss`
- Atom Feed: `http://localhost/austro-asian-times/feed/atom`
- Reader comments with editor moderation
- Image upload per article (JPG, PNG, GIF, WEBP, max 2MB)
- Mobile responsive design
- Password hashing (bcrypt)
- SQL injection protection (PDO prepared statements)
- XSS protection (htmlspecialchars on all output)
- CSRF token protection on all forms
- Role-based access control

---

## Database Structure

The database name is: `austro_asian_times`

| Table         | Description                              |
|---------------|------------------------------------------|
| users         | Journalists and editor accounts          |
| articles      | All news articles with status            |
| tags          | Keywords / folksonomy tags               |
| article_tags  | Junction table linking articles to tags  |
| comments      | Reader comments with moderation status   |

---

## Project Structure

```
austro-asian-times/
├── app/
│   ├── config/
│   │   ├── Config.php       - App settings and constants
│   │   └── Database.php     - PDO singleton connection
│   ├── controllers/
│   │   ├── Controller.php       - Base controller (auth, CSRF, helpers)
│   │   ├── AuthController.php   - Login and logout
│   │   ├── ArticleController.php - Public pages and journalist operations
│   │   ├── EditorController.php  - Editor moderation
│   │   ├── CommentController.php - Comment submission
│   │   └── FeedController.php    - RSS and Atom feeds
│   ├── models/
│   │   ├── Model.php        - Base model
│   │   ├── User.php         - User database operations
│   │   ├── Article.php      - Article database operations
│   │   ├── Tag.php          - Tag / keyword operations
│   │   └── Comment.php      - Comment database operations
│   └── views/
│       ├── layouts/         - header.php and footer.php
│       ├── auth/            - login page
│       ├── public/          - front page, article detail, tag browse
│       ├── journalist/      - dashboard, create, edit
│       └── editor/          - dashboard, comment moderation
├── public/
│   ├── css/style.css        - Responsive stylesheet
│   ├── js/main.js           - JavaScript
│   └── uploads/             - Uploaded article images stored here
├── database/
│   ├── create.sql           - Creates all tables
│   ├── load.sql             - Inserts sample data
│   └── test.sql             - Tests all CRUD operations
├── index.php                - Application entry point
├── router.php               - URL routing
└── .htaccess                - Apache URL rewriting rules
```

---

## Uninstalling

1. Delete the folder: `C:\xampp\htdocs\austro-asian-times\`
2. Open phpMyAdmin and drop the database `austro_asian_times`

---

## Security Notes

- Passwords stored using bcrypt hashing via `password_hash()`
- All database queries use PDO prepared statements
- All HTML output escaped with `htmlspecialchars()`
- CSRF tokens used on all POST forms
- File uploads validated by MIME type and size
- Role-based access control on all protected pages

---

## Technology Stack

- **Backend:** PHP 8.2
- **Database:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3, JavaScript
- **Architecture:** MVC (Model-View-Controller)
- **Server:** Apache (XAMPP)
