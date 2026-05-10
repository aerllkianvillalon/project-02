<?php
/**
 * @var array|null $auth  Current logged-in user or null
 */
$pageTitle = '404 Not Found';
$bodyClass  = 'auth-body';
?>
<?php require ROOT . '/app/Views/layouts/header.php'; ?>

<div class="error-page">
    <div class="error-content">
        <div class="error-code">404</div>
        <h2>Page Not Found</h2>
        <p>The page you're looking for doesn't exist or has been moved.</p>
        <?php if (isset($auth) && $auth): ?>
            <a href="<?= APP_URL ?>/dashboard"
               class="btn-auth"
               style="display:inline-flex;width:auto;padding:0.75rem 2rem">
                <i class="bi bi-house-fill"></i> Go to Dashboard
            </a>
        <?php else: ?>
            <a href="<?= APP_URL ?>/login"
               class="btn-auth"
               style="display:inline-flex;width:auto;padding:0.75rem 2rem">
                <i class="bi bi-arrow-left"></i> Back to Login
            </a>
        <?php endif; ?>
    </div>
</div>

<?php require ROOT . '/app/Views/layouts/footer.php'; ?>