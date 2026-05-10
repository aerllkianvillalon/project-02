<?php
// core/Controller.php — Base Controller

class Controller
{
    protected function view(string $view, array $data = []): void
    {
        // Make data variables available in view
        extract($data);

        $viewFile = ROOT . '/app/Views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            die("View <b>{$view}</b> not found at: {$viewFile}");
        }

        require $viewFile;
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . APP_URL . $path);
        exit;
    }

    protected function redirectBack(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? APP_URL . '/';
        header('Location: ' . $referer);
        exit;
    }

    protected function json(mixed $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function input(string $key, mixed $default = ''): mixed
    {
        return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
    }

    protected function file(string $key): ?array
    {
        return isset($_FILES[$key]) && $_FILES[$key]['error'] !== UPLOAD_ERR_NO_FILE
            ? $_FILES[$key]
            : null;
    }

    // ── CSRF ──────────────────────────────────────────────
    protected function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    protected function verifyCsrfToken(): void
    {
        $token = $_POST['_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(419);
            die('CSRF token mismatch. Please go back and try again.');
        }
    }

    // ── Auth helpers ──────────────────────────────────────
    protected function auth(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    protected function requireAuth(): void
    {
        if (!$this->auth()) {
            $this->setFlash('error', 'Please log in to continue.');
            $this->redirect('/login');
        }
    }

    protected function requireRole(string ...$roles): void
    {
        $this->requireAuth();
        $user = $this->auth();
        if (!in_array($user['role'], $roles)) {
            http_response_code(403);
            $this->view('errors.403');
            exit;
        }
    }

    // ── Flash messages ────────────────────────────────────
    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'][$type] = $message;
    }

    protected function getFlash(): array
    {
        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $flash;
    }

    // ── File upload helper ────────────────────────────────
    protected function uploadFile(array $file, string $subDir = ''): string|false
    {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ALLOWED_EXTENSIONS)) return false;
        if ($file['size'] > MAX_FILE_SIZE) return false;

        $dir = UPLOAD_PATH . ltrim($subDir, '/');
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $filename = uniqid('doc_', true) . '.' . $ext;
        $dest     = $dir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) return false;
        return ($subDir ? $subDir . '/' : '') . $filename;
    }
}
