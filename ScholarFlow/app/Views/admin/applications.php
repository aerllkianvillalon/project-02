<?php $pageTitle = 'All Applications'; $bodyClass = 'app-body'; ?>
<?php require ROOT . '/app/Views/layouts/header.php'; ?>

<div class="app-layout">
    <?php require ROOT . '/app/Views/layouts/sidebar.php'; ?>

    <div class="app-main">
        <header class="app-topbar">
            <button class="sidebar-toggle-btn" id="sidebarOpen"><i class="bi bi-list"></i></button>
            <div class="topbar-title">
                <h2>All Applications</h2>
                <span><?= count($apps) ?> total</span>
            </div>
        </header>

        <main class="app-content">
            <?php require ROOT . '/app/Views/layouts/flash.php'; ?>

            <div class="filter-bar">
                <div class="filter-search">
                    <i class="bi bi-search"></i>
                    <input type="text" id="appSearch" placeholder="Search applicant or scholarship..." class="search-input">
                </div>
                <div class="filter-badges">
                    <button class="filter-btn active" data-status="">All</button>
                    <button class="filter-btn" data-status="pending">Pending</button>
                    <button class="filter-btn" data-status="approved">Approved</button>
                    <button class="filter-btn" data-status="rejected">Rejected</button>
                </div>
            </div>

            <?php if (empty($apps)): ?>
                <div class="empty-state empty-state-lg">
                    <div class="empty-icon"><i class="bi bi-inbox-fill"></i></div>
                    <h4>No Applications Yet</h4>
                </div>
            <?php else: ?>
                <div class="applications-table-wrap">
                    <table class="sf-table" id="appsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Applicant</th>
                                <th>Scholarship</th>
                                <th>Applied</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($apps as $app): ?>
                                <tr data-status="<?= $app['status'] ?>"
                                    data-search="<?= strtolower($app['applicant_name'] . ' ' . $app['scholarship_name']) ?>">
                                    <td class="mono"><?= str_pad($app['id'], 5, '0', STR_PAD_LEFT) ?></td>
                                    <td>
                                        <div class="applicant-cell">
                                            <div class="mini-avatar"><?= strtoupper(substr($app['applicant_name'], 0, 2)) ?></div>
                                            <div>
                                                <strong><?= htmlspecialchars($app['applicant_name']) ?></strong>
                                                <small><?= htmlspecialchars($app['email']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($app['scholarship_name']) ?></td>
                                    <td><?= date('M j, Y', strtotime($app['created_at'])) ?></td>
                                    <td>
                                        <span class="status-badge status-<?= $app['status'] ?>">
                                            <span class="status-dot"></span>
                                            <?= ucfirst($app['status']) ?>
                                        </span>
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
const search = document.getElementById('appSearch');
const filterBtns = document.querySelectorAll('.filter-btn');
let activeFilter = '';

function filterRows() {
    const q = search.value.toLowerCase();
    document.querySelectorAll('#appsTable tbody tr').forEach(row => {
        const matchS = !activeFilter || row.dataset.status === activeFilter;
        const matchQ = row.dataset.search.includes(q);
        row.style.display = matchS && matchQ ? '' : 'none';
    });
}

search?.addEventListener('input', filterRows);
filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        activeFilter = btn.dataset.status;
        filterRows();
    });
});
</script>

<?php require ROOT . '/app/Views/layouts/footer.php'; ?>