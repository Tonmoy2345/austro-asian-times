<?php
// base controller - shared stuff for views, auth, csrf, uploads

abstract class Controller {

    // load a view with header/footer layout
    protected function view(string $viewPath, array $data = [], string $title = SITE_NAME): void {
        extract($data);
        $pageTitle = $title;
        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/' . $viewPath . '.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    // load view without layout (for xml feeds)
    protected function viewRaw(string $viewPath, array $data = []): void {
        extract($data);
        require __DIR__ . '/../views/' . $viewPath . '.php';
    }

    protected function redirect(string $path): void {
        header("Location: " . BASE_URL . $path);
        exit;
    }

    // one-time flash messages after redirect
    protected function setFlash(string $type, string $message): void {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    protected function getFlash(): array {
        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $flash;
    }

    protected function requireAuth(): void {
        if (empty($_SESSION['user_id'])) {
            $this->setFlash('warning', 'Please log in to continue.');
            $this->redirect('/login');
        }
    }

    // check user has journalist or editor role
    protected function requireRole(string $role): void {
        $this->requireAuth();
        if (($_SESSION['user_role'] ?? '') !== $role) {
            $this->setFlash('danger', 'You do not have permission to access that page.');
            $this->redirect('/');
        }
    }

    protected function isLoggedIn(): bool {
        return !empty($_SESSION['user_id']);
    }

    protected function isEditor(): bool {
        return ($this->isLoggedIn() && $_SESSION['user_role'] === 'editor');
    }

    // csrf token for forms
    protected function generateCsrf(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    protected function validateCsrf(): void {
        $token = $_POST['csrf_token'] ?? '';
        if (empty($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            die("Invalid security token. Please go back and try again.");
        }
    }

    // upload image, returns filename or null
    protected function handleImageUpload(string $fieldName): ?string {
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $file = $_FILES[$fieldName];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->setFlash('danger', 'Image upload error. Please try again.');
            return null;
        }

        if ($file['size'] > MAX_FILE_SIZE) {
            $this->setFlash('danger', 'Image file too large. Maximum size is 2MB.');
            return null;
        }

        // check actual file type with finfo (more reliable than $_FILES type)
        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, ALLOWED_TYPES, true)) {
            $this->setFlash('danger', 'Invalid file type. Only JPG, PNG, GIF and WEBP images are allowed.');
            return null;
        }

        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = uniqid('img_', true) . '.' . $ext;
        $dest     = UPLOAD_DIR . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            $this->setFlash('danger', 'Could not save the uploaded image.');
            return null;
        }

        return $filename;
    }

    // escape output to prevent xss
    protected function e(string $str): string {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
}

// helper for views
function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// e.g. "3 hours ago"
function timeAgo(string $datetime): string {
    $now  = new DateTime();
    $then = new DateTime($datetime);
    $diff = $now->diff($then);

    if ($diff->y > 0)  return $diff->y  . ' year'   . ($diff->y  > 1 ? 's' : '') . ' ago';
    if ($diff->m > 0)  return $diff->m  . ' month'  . ($diff->m  > 1 ? 's' : '') . ' ago';
    if ($diff->d > 0)  return $diff->d  . ' day'    . ($diff->d  > 1 ? 's' : '') . ' ago';
    if ($diff->h > 0)  return $diff->h  . ' hour'   . ($diff->h  > 1 ? 's' : '') . ' ago';
    if ($diff->i > 0)  return $diff->i  . ' minute' . ($diff->i  > 1 ? 's' : '') . ' ago';
    return 'just now';
}
