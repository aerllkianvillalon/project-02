<?php
$auth        = $auth ?? [];
$role        = $auth['role'] ?? 'student';
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath    = '/ScholarFlow/public';
$path        = str_replace($basePath, '', $currentPath);

function navItem(string $href, string $icon, string $label, string $current): string {
    // Active only when the current path matches the link exactly (no prefix matching)
    $active = (rtrim($current, '/') === rtrim($href, '/'));

    $cls = $active ? 'active' : '';
    return "<a href=\"" . APP_URL . $href . "\" class=\"nav-link {$cls}\">
        <i class=\"bi bi-{$icon}\"></i><span>{$label}</span>
    </a>";
}
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
        <span class="brand-text">Scholar<strong>Flow</strong></span>
<button class="sidebar-toggle d-lg-none" id="sidebarClose" type="button">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <div class="sidebar-user">
        <div class="user-avatar">
            <?php if (!empty($auth['avatar'])): ?>
                <img src="<?= APP_URL . '/uploads/' . htmlspecialchars($auth['avatar']) ?>" alt="">
            <?php else: ?>
                <span><?= strtoupper(substr($auth['name'], 0, 2)) ?></span>
            <?php endif; ?>
        </div>
        <div class="user-info">
            <div class="user-name"><?= htmlspecialchars($auth['name']) ?></div>
            <div class="user-role badge-role badge-<?= $role ?>"><?= ucfirst($role) ?></div>
        </div>
    </div>

    <nav class="sidebar-nav" id="sidebarNav">
        <?php if ($role === 'student'): ?>
            <?= navItem('/dashboard', 'house-fill', 'Dashboard', $path) ?>
            <?= navItem('/scholarships', 'award-fill', 'Scholarships', $path) ?>
            <?= navItem('/applications', 'file-earmark-text-fill', 'My Applications', $path) ?>
            <?= navItem('/profile', 'person-fill', 'Profile', $path) ?>


        <?php elseif ($role === 'reviewer'): ?>
            <?= navItem('/reviewer', 'speedometer2', 'Dashboard', $path) ?>
            <?= navItem('/reviewer/applications', 'file-earmark-check-fill', 'Applications', $path) ?>

        <?php elseif ($role === 'admin'): ?>
            <?= navItem('/admin', 'grid-fill', 'Dashboard', $path) ?>
            <?= navItem('/admin/users', 'people-fill', 'Users', $path) ?>
            <?= navItem('/admin/scholarships', 'trophy-fill', 'Scholarships', $path) ?>
            <?= navItem('/admin/applications', 'file-earmark-text-fill', 'Applications', $path) ?>

        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <a href="<?= APP_URL ?>/logout" class="nav-link logout-link">
            <i class="bi bi-box-arrow-left"></i><span>Sign Out</span>
        </a>
    </div>
</aside>