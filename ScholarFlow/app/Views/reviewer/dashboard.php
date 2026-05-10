<?php $pageTitle = 'Reviewer Dashboard'; $bodyClass = 'app-body'; ?>
<?php require ROOT . '/app/Views/layouts/header.php'; ?>

<div class="app-layout">
    <?php require ROOT . '/app/Views/layouts/sidebar.php'; ?>

    <div class="app-main">
        <header class="app-topbar">
            <button class="sidebar-toggle-btn" id="sidebarOpen"><i class="bi bi-list"></i></button>
            <div class="topbar-title">
                <h2>Reviewer Dashboard</h2>
                <span><?= date('l, F j, Y') ?></span>
            </div>
        </header>

        <main class="app-content">
            <?php require ROOT . '/app/Views/layouts/flash.php'; ?>

            <div class="stats-grid">
                <div class="stat-card stat-pending">
                    <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
                    <div class="stat-info">
                        <span class="stat-num"><?= $stats['pending'] ?? 0 ?></span>
                        <span class="stat-label">Pending Review</span>
                    </div>
                </div>
                <div class="stat-card stat-approved">
                    <div class="stat-icon"><i class="bi bi-patch-check-fill"></i></div>
                    <div class="stat-info">
                        <span class="stat-num"><?= $stats['approved'] ?? 0 ?></span>
                        <span class="stat-label">Approved</span>
                    </div>
                </div>
                <div class="stat-card stat-rejected">
                    <div class="stat-icon"><i class="bi bi-x-circle-fill"></i></div>
                    <div class="stat-info">
                        <span class="stat-num"><?= $stats['rejected'] ?? 0 ?></span>
                        <span class="stat-label">Rejected</span>
                    </div>
                </div>
            </div>

            <div class="content-card">
                <div class="card-header-row">
                    <h4>Applications Awaiting Review</h4>
                    <a href="<?= APP_URL ?>/reviewer/applications" class="link-sm">
                        All applications <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <?php if (empty($pending)): ?>
                    <div class="empty-state">
                        <i class="bi bi-check-all"></i>
                        <p>No pending applications. All caught up!</p>
                    </div>
                <?php else: ?>
                    <div class="review-queue">
                        <?php foreach ($pending as $app): ?>
                            <div class="review-queue-item">
                                <div class="rq-applicant">
                                    <div class="rq-avatar">
                                        <?= strtoupper(substr($app['applicant_name'], 0, 2)) ?>
                                    </div>
                                    <div class="rq-info">
                                        <strong><?= htmlspecialchars($app['applicant_name']) ?></strong>
                                        <small><?= htmlspecialchars($app['applicant_email']) ?></small>
                                    </div>
                                </div>
                                <div class="rq-scholarship">
                                    <span><?= htmlspecialchars($app['scholarship_name']) ?></span>
                                </div>
                                <div class="rq-date">
                                    <?= date('M j, Y', strtotime($app['created_at'])) ?>
                                </div>
                                <span class="status-badge status-pending">
                                    <span class="status-dot"></span> Pending
                                </span>
                                <a href="<?= APP_URL ?>/reviewer/applications/<?= $app['id'] ?>"
                                   class="btn-review">
                                    Review <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<?php require ROOT . '/app/Views/layouts/footer.php'; ?>