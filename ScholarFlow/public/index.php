<?php
/**
 * ScholarFlow — Public Entry Point
 * All HTTP requests are routed through this file via .htaccess
 */

define('ROOT', dirname(__DIR__));

// ── Load config ───────────────────────────────────────────────
require_once ROOT . '/config/database.php';

// ── Autoload core classes ─────────────────────────────────────
require_once ROOT . '/core/App.php';
require_once ROOT . '/core/Router.php';
require_once ROOT . '/core/Controller.php';
require_once ROOT . '/core/Model.php';

// ── Run the application ───────────────────────────────────────
App::run();
