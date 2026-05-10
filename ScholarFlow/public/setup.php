<?php
/**
 * ScholarFlow — One-Time Seeder Script
 *
 * Run this ONCE via browser: http://localhost/ScholarFlow/public/setup.php
 * Then DELETE this file immediately after!
 *
 * Usage: php setup.php  (or visit via browser)
 */

define('ROOT', __DIR__ . '/..');
require_once ROOT . '/config/database.php';
require_once ROOT . '/core/App.php';
require_once ROOT . '/core/Router.php';
require_once ROOT . '/core/Controller.php';
require_once ROOT . '/core/Model.php';

// Simple security: only run from localhost
if (!in_array($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1', ['127.0.0.1', '::1', 'localhost'])) {
    die('Setup script can only run from localhost.');
}

$db = App::db();

$results = [];

try {
    // ── Create tables ─────────────────────────────────────
    $sqlFile = ROOT . '/scholarflow_db.sql';
    if (file_exists($sqlFile)) {
        $sql = file_get_contents($sqlFile);
        // Split on semicolons for multi-statement execution
        $statements = array_filter(
            array_map('trim', explode(';', $sql)),
            fn($s) => !empty($s) && !str_starts_with(ltrim($s), '--')
        );
        foreach ($statements as $stmt) {
            if (empty(trim($stmt))) continue;
            try {
                $db->exec($stmt);
            } catch (PDOException $e) {
                // Skip duplicate key errors on re-run
                if ($e->getCode() !== '23000') {
                    $results[] = ['status' => 'warn', 'msg' => 'Skipped: ' . substr($e->getMessage(), 0, 100)];
                }
            }
        }
        $results[] = ['status' => 'ok', 'msg' => 'Schema and seed data applied.'];
    } else {
        $results[] = ['status' => 'warn', 'msg' => 'SQL file not found — skipping schema.'];
    }

    // ── Ensure admin exists with correct bcrypt hash ──────
    $adminEmail = 'admin@scholarflow.com';
    $stmt = $db->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$adminEmail]);
    $existing = $stmt->fetch();

    if (!$existing) {
        $hash = password_hash('Admin@1234', PASSWORD_BCRYPT);
        $db->prepare(
            "INSERT INTO users (name, email, password, role, created_at)
             VALUES ('System Admin', ?, ?, 'admin', NOW())"
        )->execute([$adminEmail, $hash]);
        $results[] = ['status' => 'ok', 'msg' => 'Admin user created: admin@scholarflow.com / Admin@1234'];
    } else {
        // Update hash to ensure it's correct bcrypt
        $hash = password_hash('Admin@1234', PASSWORD_BCRYPT);
        $db->prepare('UPDATE users SET password = ? WHERE email = ?')
           ->execute([$hash, $adminEmail]);
        $results[] = ['status' => 'ok', 'msg' => 'Admin password reset: admin@scholarflow.com / Admin@1234'];
    }

    // Reviewer
    $revEmail = 'reviewer@scholarflow.com';
    $stmt = $db->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$revEmail]);
    if (!$stmt->fetch()) {
        $hash = password_hash('Admin@1234', PASSWORD_BCRYPT);
        $db->prepare(
            "INSERT INTO users (name, email, password, role, created_at)
             VALUES ('Jane Reviewer', ?, ?, 'reviewer', NOW())"
        )->execute([$revEmail, $hash]);
        $results[] = ['status' => 'ok', 'msg' => 'Reviewer created: reviewer@scholarflow.com / Admin@1234'];
    } else {
        $hash = password_hash('Admin@1234', PASSWORD_BCRYPT);
        $db->prepare('UPDATE users SET password = ? WHERE email = ?')
           ->execute([$hash, $revEmail]);
        $results[] = ['status' => 'ok', 'msg' => 'Reviewer password reset.'];
    }

    // Student
    $stuEmail = 'student@scholarflow.com';
    $stmt = $db->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$stuEmail]);
    if (!$stmt->fetch()) {
        $hash = password_hash('Student@1234', PASSWORD_BCRYPT);
        $db->prepare(
            "INSERT INTO users (name, email, password, role, phone, school, course, gpa, year_level, created_at)
             VALUES ('Juan dela Cruz', ?, ?, 'student', '+63 912 345 6789',
                     'Cebu Institute of Technology', 'BS Computer Science', '1.50', '3rd Year', NOW())"
        )->execute([$stuEmail, $hash]);
        $results[] = ['status' => 'ok', 'msg' => 'Demo student created: student@scholarflow.com / Student@1234'];
    } else {
        $results[] = ['status' => 'info', 'msg' => 'Demo student already exists.'];
    }

    // ── Ensure upload directories ─────────────────────────
    $dirs = [UPLOAD_PATH, UPLOAD_PATH . 'documents/', UPLOAD_PATH . 'avatars/'];
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
            $results[] = ['status' => 'ok', 'msg' => 'Created directory: ' . $dir];
        }
    }

} catch (Exception $e) {
    $results[] = ['status' => 'error', 'msg' => 'Error: ' . $e->getMessage()];
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ScholarFlow — Setup</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; background: #0f172a; color: #e2e8f0; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .setup-card { background: #1e293b; border-radius: 16px; padding: 2.5rem; max-width: 600px; width: 90%; box-shadow: 0 8px 32px rgba(0,0,0,.4); }
        h1 { font-family: 'Syne', sans-serif; font-size: 1.75rem; color: #fff; margin-bottom: 0.5rem; }
        h1 span { color: #7c3aed; }
        p.sub { color: #94a3b8; margin-bottom: 2rem; font-size: 0.875rem; }
        .result { display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 0.5rem; font-size: 0.875rem; }
        .result.ok    { background: #064e3b; color: #6ee7b7; }
        .result.error { background: #7f1d1d; color: #fca5a5; }
        .result.warn  { background: #78350f; color: #fcd34d; }
        .result.info  { background: #1e3a5f; color: #93c5fd; }
        .login-info { margin-top: 2rem; background: #0f172a; border-radius: 10px; padding: 1.25rem; }
        .login-info h3 { font-family: 'Syne', sans-serif; color: #7c3aed; margin-bottom: 0.875rem; }
        .cred-row { display: flex; gap: 1rem; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid #1e293b; font-size: 0.875rem; }
        .cred-role { width: 80px; font-weight: 600; color: #94a3b8; text-transform: uppercase; font-size: 0.75rem; }
        .cred-email { color: #e2e8f0; }
        .cred-pass  { color: #7c3aed; font-family: monospace; }
        .actions { margin-top: 1.5rem; display: flex; gap: 1rem; }
        a.btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; border-radius: 8px; font-size: 0.9rem; font-weight: 600; text-decoration: none; transition: opacity 0.2s; }
        a.btn:hover { opacity: 0.85; }
        .btn-primary { background: #7c3aed; color: #fff; }
        .btn-danger  { background: #dc2626; color: #fff; font-size: 0.8rem; }
        .warning-box { background: #7f1d1d; border-radius: 8px; padding: 1rem; margin-top: 1rem; font-size: 0.8rem; color: #fca5a5; }
    </style>
</head>
<body>
<div class="setup-card">
    <h1>Scholar<span>Flow</span> Setup</h1>
    <p class="sub">Database initialization and seeding</p>

    <?php foreach ($results as $r): ?>
        <div class="result <?= $r['status'] ?>">
            <?= $r['status'] === 'ok' ? '✓' : ($r['status'] === 'error' ? '✗' : '⚠') ?>
            <?= htmlspecialchars($r['msg']) ?>
        </div>
    <?php endforeach; ?>

    <div class="login-info">
        <h3>Default Login Credentials</h3>
        <div class="cred-row">
            <span class="cred-role">Admin</span>
            <span class="cred-email">admin@scholarflow.com</span>
            <span class="cred-pass">Admin@1234</span>
        </div>
        <div class="cred-row">
            <span class="cred-role">Reviewer</span>
            <span class="cred-email">reviewer@scholarflow.com</span>
            <span class="cred-pass">Admin@1234</span>
        </div>
        <div class="cred-row">
            <span class="cred-role">Student</span>
            <span class="cred-email">student@scholarflow.com</span>
            <span class="cred-pass">Student@1234</span>
        </div>
    </div>

    <div class="actions">
        <a href="<?= APP_URL ?>/" class="btn btn-primary">🚀 Go to ScholarFlow</a>
    </div>

    <div class="warning-box">
        ⚠ <strong>Security:</strong> Delete this file (<code>public/setup.php</code>) after setup!
    </div>
</div>
</body>
</html>
