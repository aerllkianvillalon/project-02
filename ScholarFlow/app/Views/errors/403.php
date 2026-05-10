<?php $pageTitle = '403 Forbidden'; $bodyClass = 'auth-body'; ?>
<?php require ROOT . '/app/Views/layouts/header.php'; ?>

<div class="error-page">
    <div class="error-content">
        <div class="error-code">403</div>
        <h2>Access Denied</h2>
        <p>You don't have permission to access this page.</p>
        <a href="<?= APP_URL ?>/dashboard" class="btn-auth" style="display:inline-flex;width:auto;padding:0.75rem 2rem">
            <i class="bi bi-house-fill"></i> Go to Dashboard
        </a>
    </div>
</div>

<?php require ROOT . '/app/Views/layouts/footer.php'; ?>