<?php $pageTitle = 'Manage Scholarships'; $bodyClass = 'app-body'; ?>
<?php require ROOT . '/app/Views/layouts/header.php'; ?>

<div class="app-layout">
    <?php require ROOT . '/app/Views/layouts/sidebar.php'; ?>

    <div class="app-main">
        <header class="app-topbar">
            <button class="sidebar-toggle-btn" id="sidebarOpen"><i class="bi bi-list"></i></button>
            <div class="topbar-title">
                <h2>Scholarships</h2>
                <span><?= count($scholarships) ?> scholarship(s)</span>
            </div>
            <div class="topbar-actions">
                <a href="<?= APP_URL ?>/admin/scholarships/create" class="btn-topbar">
                    <i class="bi bi-plus-lg"></i> Add Scholarship
                </a>
            </div>
        </header>

        <main class="app-content">
            <?php require ROOT . '/app/Views/layouts/flash.php'; ?>

            <?php if (empty($scholarships)): ?>
                <div class="empty-state empty-state-lg">
                    <div class="empty-icon"><i class="bi bi-trophy"></i></div>
                    <h4>No Scholarships Yet</h4>
                    <a href="<?= APP_URL ?>/admin/scholarships/create" class="btn-primary-outline">
                        <i class="bi bi-plus-lg"></i> Create First Scholarship
                    </a>
                </div>
            <?php else: ?>
                <div class="applications-table-wrap">
                    <table class="sf-table">
                        <thead>
                            <tr>
                                <th>Scholarship</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Deadline</th>
                                <th>Apps</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($scholarships as $s): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($s['name']) ?></strong>
                                        <br><small class="text-muted"><?= htmlspecialchars(substr($s['description'], 0, 60)) ?>...</small>
                                    </td>
                                    <td><span class="amount-text">₱<?= number_format($s['amount']) ?></span></td>
                                    <td>
                                        <span class="type-pill <?= $s['allows_multiple'] ? 'type-open' : 'type-exclusive' ?>">
                                            <i class="bi bi-<?= $s['allows_multiple'] ? 'infinity' : 'star-fill' ?>"></i>
                                            <?= $s['allows_multiple'] ? 'Open' : 'Exclusive' ?>
                                        </span>
                                    </td>
                                    <td><?= date('M j, Y', strtotime($s['deadline'])) ?></td>
                                    <td>
                                        <span class="app-count-badge"><?= $s['application_count'] ?></span>
                                    </td>
                                    <td>
                                        <span class="badge-status-sm badge-<?= $s['status'] ?>"><?= ucfirst($s['status']) ?></span>
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            <a href="<?= APP_URL ?>/admin/scholarships/<?= $s['id'] ?>/edit"
                                               class="btn-icon-action" title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <form method="POST"
                                                  action="<?= APP_URL ?>/admin/scholarships/<?= $s['id'] ?>/delete"
                                                  onsubmit="return confirm('Delete this scholarship and all its applications?')">
                                                <input type="hidden" name="_token"
                                                       value="<?php
                                                           if (empty($_SESSION['csrf_token'])) {
                                                               $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                                                           }
                                                           echo $_SESSION['csrf_token'];
                                                       ?>">
                                                <button type="submit" class="btn-icon-action btn-danger-icon" title="Delete">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </form>
                                        </div>
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

<?php require ROOT . '/app/Views/layouts/footer.php'; ?>