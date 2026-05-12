<?php
/**
 * Student Dashboard
 *
 * @var array      $auth                   Current user
 * @var array[]    $myApplications         Student's own applications
 * @var array      $stats                  ['total','pending','approved','rejected']
 * @var array[]    $availableScholarships  Scholarships with 'already_applied' & 'locked' flags
 * @var array      $flash                  Flash messages
 */
$pageTitle = 'Dashboard';
$bodyClass  = 'app-body';
?>
<?php require ROOT . '/app/Views/layouts/header.php'; ?>

<div class="app-layout">
    <?php require ROOT . '/app/Views/layouts/sidebar.php'; ?>

    <div class="app-main">
        <header class="app-topbar">
            <button class="sidebar-toggle-btn" id="sidebarOpen"><i class="bi bi-list"></i></button>
            <div class="topbar-title">
                <h2>Dashboard</h2>
                <span><?= date('l, F j, Y') ?></span>
            </div>
        </header>

        <main class="app-content">
            <?php require ROOT . '/app/Views/layouts/flash.php'; ?>

            <!-- Welcome Banner -->
            <div class="welcome-banner">
                <div class="welcome-text">
                    <h3>Welcome, <?= htmlspecialchars(explode(' ', $auth['name'])[0]) ?>! 👋</h3>
                    <p>Track your applications and discover new scholarships.</p>
                </div>
                <a href="<?= APP_URL ?>/scholarships" class="btn-primary-sm">
                    <i class="bi bi-plus-lg"></i> Browse Scholarships
                </a>
            </div>

            <!-- Stats Row -->
            <div class="stats-grid">
                <div class="stat-card stat-total">
                    <div class="stat-icon"><i class="bi bi-files"></i></div>
                    <div class="stat-info">
                        <span class="stat-num"><?= $stats['total'] ?></span>
                        <span class="stat-label">Total Applications</span>
                    </div>
                </div>
                <div class="stat-card stat-pending">
                    <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
                    <div class="stat-info">
                        <span class="stat-num"><?= $stats['pending'] ?></span>
                        <span class="stat-label">Under Review</span>
                    </div>
                </div>
                <div class="stat-card stat-approved">
                    <div class="stat-icon"><i class="bi bi-patch-check-fill"></i></div>
                    <div class="stat-info">
                        <span class="stat-num"><?= $stats['approved'] ?></span>
                        <span class="stat-label">Approved</span>
                    </div>
                </div>
                <div class="stat-card stat-rejected">
                    <div class="stat-icon"><i class="bi bi-x-circle-fill"></i></div>
                    <div class="stat-info">
                        <span class="stat-num"><?= $stats['rejected'] ?></span>
                        <span class="stat-label">Rejected</span>
                    </div>
                </div>
            </div>

            <div class="content-grid">
                <!-- Recent Applications -->
                <div class="content-card">
                    <div class="card-header-row">
                        <h4>Recent Applications</h4>
                        <a href="<?= APP_URL ?>/applications" class="link-sm">
                            View all <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                    <?php if (empty($myApplications)): ?>
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p>No applications yet. Start exploring scholarships!</p>
                            <a href="<?= APP_URL ?>/scholarships" class="btn-outline-sm">Browse Now</a>
                        </div>
                    <?php else: ?>
                        <div class="app-list">
                            <?php foreach (array_slice($myApplications, 0, 5) as $application): ?>
                                <a href="<?= APP_URL ?>/applications/<?= $application['id'] ?>"
                                   class="app-list-item">
                                    <div class="app-item-icon"><i class="bi bi-award"></i></div>
                                    <div class="app-item-info">
                                        <strong><?= htmlspecialchars($application['scholarship_name']) ?></strong>
                                        <span>
                                            ₱<?= number_format($application['amount']) ?>
                                            &bull; <?= date('M j, Y', strtotime($application['created_at'])) ?>
                                        </span>
                                    </div>
                                    <span class="status-badge status-<?= $application['status'] ?>">
                                        <?= ucfirst($application['status']) ?>
                                    </span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Available Scholarships -->
                <div class="content-card">
                    <div class="card-header-row">
                        <h4>Available Scholarships</h4>
                        <a href="<?= APP_URL ?>/scholarships" class="link-sm">
                            View all <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                    <?php
                    $available = array_filter(
                        $availableScholarships,
                        static fn(array $s): bool => !$s['already_applied'] && !$s['locked']
                    );
                    ?>
                    <?php if (empty($available)): ?>
                        <div class="empty-state">
                            <i class="bi bi-trophy"></i>
                            <p>No new scholarships available right now.</p>
                        </div>
                    <?php else: ?>
                        <div class="scholarship-mini-list">
                            <?php foreach (array_slice($available, 0, 4) as $s): ?>
                                <div class="scholarship-mini-card">
                                    <div class="schol-mini-header">
                                        <?php if (!$s['allows_multiple']): ?>
                                            <span class="badge-exclusive">
                                                <i class="bi bi-star-fill"></i> Exclusive
                                            </span>
                                        <?php else: ?>
                                            <span class="badge-open">
                                                <i class="bi bi-check-circle"></i> Open
                                            </span>
                                        <?php endif; ?>
                                        <span class="schol-amount">
                                            ₱<?= number_format($s['amount']) ?>
                                        </span>
                                    </div>
                                    <h5><?= htmlspecialchars($s['name']) ?></h5>
                                    <p><?= htmlspecialchars(substr($s['description'], 0, 80)) ?>…</p>
                                    <div class="schol-mini-footer">
                                        <span class="deadline-txt">
                                            <i class="bi bi-calendar3"></i>
                                            <?= date('M j', strtotime($s['deadline'])) ?>
                                        </span>
                                        <a href="<?= APP_URL ?>/apply/<?= $s['id'] ?>"
                                           class="btn-apply-sm">Apply</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require ROOT . '/app/Views/layouts/footer.php'; ?>