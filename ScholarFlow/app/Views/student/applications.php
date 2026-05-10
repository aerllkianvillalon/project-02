<?php $pageTitle = 'My Applications'; $bodyClass = 'app-body'; ?>
<?php require ROOT . '/app/Views/layouts/header.php'; ?>

<div class="app-layout">
    <?php require ROOT . '/app/Views/layouts/sidebar.php'; ?>

    <div class="app-main">
        <header class="app-topbar">
            <button class="sidebar-toggle-btn" id="sidebarOpen"><i class="bi bi-list"></i></button>
            <div class="topbar-title">
                <h2>My Applications</h2>
                <span>Track your scholarship applications</span>
            </div>
        </header>

        <main class="app-content">
            <?php require ROOT . '/app/Views/layouts/flash.php'; ?>

            <!-- Status Filter Tabs -->
            <div class="tab-filter-bar">
                <button class="tab-filter active" data-status="">All</button>
                <button class="tab-filter" data-status="pending">
                    <span class="dot dot-pending"></span> Pending
                </button>
                <button class="tab-filter" data-status="approved">
                    <span class="dot dot-approved"></span> Approved
                </button>
                <button class="tab-filter" data-status="rejected">
                    <span class="dot dot-rejected"></span> Rejected
                </button>
            </div>

            <?php if (empty($applications)): ?>
                <div class="empty-state empty-state-lg">
                    <div class="empty-icon"><i class="bi bi-inbox-fill"></i></div>
                    <h4>No Applications Yet</h4>
                    <p>You haven't applied to any scholarships. Start exploring available scholarships!</p>
                    <a href="<?= APP_URL ?>/scholarships" class="btn-primary-outline">
                        <i class="bi bi-search"></i> Browse Scholarships
                    </a>
                </div>
            <?php else: ?>
                <div class="applications-table-wrap" id="applicationsContainer">
                    <table class="sf-table" id="applicationsTable">
                        <thead>
                            <tr>
                                <th>Scholarship</th>
                                <th>Amount</th>
                                <th>Applied</th>
                                <th>Status</th>
                                <th>Reviewed By</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applications as $app): ?>
                                <tr data-status="<?= $app['status'] ?>">
                                    <td>
                                        <div class="schol-name-cell">
                                            <div class="schol-icon-sm">
                                                <i class="bi bi-award"></i>
                                            </div>
                                            <div>
                                                <strong><?= htmlspecialchars($app['scholarship_name']) ?></strong>
                                                <small><?= $app['allows_multiple'] ? 'Open' : 'Exclusive' ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="amount-text">₱<?= number_format($app['amount']) ?></span></td>
                                    <td><?= date('M j, Y', strtotime($app['created_at'])) ?></td>
                                    <td>
                                        <span class="status-badge status-<?= $app['status'] ?>">
                                            <span class="status-dot"></span>
                                            <?= ucfirst($app['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= $app['reviewer_name'] ? htmlspecialchars($app['reviewer_name']) : '<span class="text-muted">—</span>' ?></td>
                                    <td>
                                        <a href="<?= APP_URL ?>/applications/<?= $app['id'] ?>" class="btn-table-action">
                                            View <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<script>
document.querySelectorAll('.tab-filter').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-filter').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const status = btn.dataset.status;
        document.querySelectorAll('#applicationsTable tbody tr').forEach(row => {
            row.style.display = (!status || row.dataset.status === status) ? '' : 'none';
        });
    });
});
</script>

<?php require ROOT . '/app/Views/layouts/footer.php'; ?>