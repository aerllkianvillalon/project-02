<?php
/**
 * Flash message partial.
 *
 * @var array $flash  Keys: 'success' | 'error' | 'info', value: string message
 */
$flash = $flash ?? [];
?>
<?php if (!empty($flash)): ?>
    <?php foreach ($flash as $type => $message): ?>
        <div class="alert-toast alert-toast-<?= htmlspecialchars($type) ?>" role="alert">
            <i class="bi bi-<?= $type === 'success'
                ? 'check-circle-fill'
                : ($type === 'error' ? 'exclamation-triangle-fill' : 'info-circle-fill') ?>"></i>
            <span><?= $message ?></span>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="bi bi-x"></i>
            </button>
        </div>
    <?php endforeach; ?>
<?php endif; ?>