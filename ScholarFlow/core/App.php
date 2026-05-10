<?php
// core/App.php — Bootstrap & Router

class App
{
    private static ?PDO $db = null;

    public static function run(): void
    {
        // Start session securely
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_strict_mode', 1);
            session_start();
        }

        // Regenerate session periodically (anti-fixation)
        if (!isset($_SESSION['_created'])) {
            $_SESSION['_created'] = time();
        } elseif (time() - $_SESSION['_created'] > 1800) {
            session_regenerate_id(true);
            $_SESSION['_created'] = time();
        }

        // Get the URI and method
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // Strip common base paths if running behind subdirectory /public
        // This makes routes work for URLs like:
        // - /ScholarFlow/public/admin
        // - /ScholarFlow/admin
        // - /public/admin
        $candidates = [
            '/ScholarFlow/public',
            '/public',
        ];
        foreach ($candidates as $basePath) {
            if (strpos($uri, $basePath) === 0) {
                $uri = substr($uri, strlen($basePath));
                break;
            }
        }
        $uri = '/' . trim($uri, '/') ?: '/';

        // Load routes
        $router = new Router();
        require_once ROOT . '/routes/web.php';
        $router->dispatch($uri, $method);
    }

    public static function db(): PDO
    {
        if (self::$db === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                DB_HOST, DB_PORT, DB_NAME, DB_CHARSET
            );
            try {
                self::$db = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                if (APP_ENV === 'development') {
                    die('<b>DB Connection Error:</b> ' . htmlspecialchars($e->getMessage()));
                }
                die('A database error occurred. Please try again later.');
            }
        }
        return self::$db;
    }
}
