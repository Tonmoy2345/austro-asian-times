<?php
// login, logout, session handling

class AuthController extends Controller {

    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showLogin(): void {
        if ($this->isLoggedIn()) {
            $this->redirectToDashboard();
        }

        $csrf  = $this->generateCsrf();
        $flash = $this->getFlash();
        $this->view('auth/login', ['csrf' => $csrf, 'flash' => $flash], 'Login');
    }

    public function login(): void {
        $this->validateCsrf();

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $this->setFlash('danger', 'Please enter both username and password.');
            $this->redirect('/login');
        }

        $user = $this->userModel->findByUsername($username);

        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            $this->setFlash('danger', 'Incorrect username or password.');
            $this->redirect('/login');
        }

        session_regenerate_id(true);

        $_SESSION['user_id']       = $user['id'];
        $_SESSION['user_username'] = $user['username'];
        $_SESSION['user_role']     = $user['role'];

        $this->setFlash('success', 'Welcome back, ' . $user['username'] . '!');
        $this->redirectToDashboard();
    }

    public function logout(): void {
        session_unset();
        session_destroy();
        session_start();
        $this->setFlash('success', 'You have been logged out.');
        $this->redirect('/login');
    }

    private function redirectToDashboard(): void {
        if ($_SESSION['user_role'] === 'editor') {
            $this->redirect('/editor/dashboard');
        } else {
            $this->redirect('/journalist/dashboard');
        }
    }
}
