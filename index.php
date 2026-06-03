<?php
// main entry point - .htaccess routes everything here

session_start();

require_once __DIR__ . '/app/config/Config.php';
require_once __DIR__ . '/app/config/Database.php';

require_once __DIR__ . '/app/models/Model.php';
require_once __DIR__ . '/app/controllers/Controller.php';

require_once __DIR__ . '/app/models/User.php';
require_once __DIR__ . '/app/models/Article.php';
require_once __DIR__ . '/app/models/Tag.php';
require_once __DIR__ . '/app/models/Comment.php';

require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/ArticleController.php';
require_once __DIR__ . '/app/controllers/EditorController.php';
require_once __DIR__ . '/app/controllers/CommentController.php';
require_once __DIR__ . '/app/controllers/FeedController.php';

require_once __DIR__ . '/router.php';
