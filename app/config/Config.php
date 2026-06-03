<?php
// app settings - update BASE_URL if your folder name is different

define('BASE_URL', '/austro-asian-times');

// xampp defaults
define('DB_HOST', 'localhost');
define('DB_NAME', 'austro_asian_times');
define('DB_USER', 'root');
define('DB_PASS', '');

// image uploads
define('UPLOAD_DIR',     __DIR__ . '/../../public/uploads/');
define('UPLOAD_URL',     BASE_URL . '/public/uploads/');
define('MAX_FILE_SIZE',  2 * 1024 * 1024); // 2MB max
define('ALLOWED_TYPES',  ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
define('ALLOWED_EXTS',   ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// site name and feed info
define('SITE_NAME',        'Austro-Asian Times');
define('SITE_DESCRIPTION', 'News for Northern Australia and Southeast Asia');
define('SITE_URL',         'http://localhost' . BASE_URL);
