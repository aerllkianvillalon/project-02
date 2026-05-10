<?php
/**
 * Admin Dashboard
 *
 * @var array   $auth                Current user
 * @var array   $userCounts          Keyed by role: ['student'=>int,'reviewer'=>int,'admin'=>int]
 * @var array   $appCounts           Keyed by status: ['pending'=>int,'approved'=>int,'rejected'=>int]
 * @var array[] $recentApplications  Latest 10 applications (joined scholarship + user)
 * @var array[] $scholarships        All scholarships with application_count stat
 * @var array   $flash               Flash messages
 */
$pageTitle = 'Admin Dashboard';
$bodyClass  = 'app-body';
?>

<?php require ROOT . '/app/Views/layouts/header.php'; ?>

<div class="app-layout">
    <div style="position:relative; z-index:101;">
    <?php require ROOT . '/app/Views/layouts/sidebar.php'; ?>
    </div>

    <div class="app-main" style="position:relative; z-index:1;">
        <header class="app-topbar">
            <button class="sidebar-toggle-btn" id="sidebarOpen"><i class="bi bi-list"></i></button>
            <div class="topbar-title">
                <h2>Admin Dashboard</h2>
                <span><?= date('l, F j, Y') ?></span>
            </div>
        </header>

        <main class="app-content">
            <?php require ROOT . '/app/Views/layouts/flash.php'; ?>

            <!-- System Overview -->
            <div class="section-label-row"><h4>System Overview</h4></div>
            <div class="stats-grid stats-grid-5">
                <div class="stat-card stat-total">
                    <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                    <div class="stat-info">
                        <span class="stat-num">
                            <?= array_sum($userCounts) ?>
                        </span>
                        <span class="stat-label">Total Users</span>
                    </div>
                </div>
                <div class="stat-card" style="--stat-color:#6366f1">
                    <div class="stat-icon"><i class="bi bi-person-fill"></i></div>
                    <div class="stat-info">
                        <span class="stat-num"><?= $userCounts['student']  ?? 0 ?></span>
                        <span class="stat-label">Students</span>
                    </div>
                </div>
                <div class="stat-card" style="--stat-color:#8b5cf6">
                    <div class="stat-icon"><i class="bi bi-person-check-fill"></i></div>
                    <div class="stat-info">
                        <span class="stat-num"><?= $userCounts['reviewer'] ?? 0 ?></span>
                        <span class="stat-label">Reviewers</span>
                    </div>
                </div>
                <div class="stat-card stat-pending">
                    <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
                    <div class="stat-info">
                        <span class="stat-num"><?= $appCounts['pending']   ?? 0 ?></span>
                        <span class="stat-label">Pending Apps</span>
                    </div>
                </div>
                <div class="stat-card stat-approved">
                    <div class="stat-icon"><i class="bi bi-patch-check-fill"></i></div>
                    <div class="stat-info">
                        <span class="stat-num"><?= $appCounts['approved']  ?? 0 ?></span>
                        <span class="stat-label">Approved</span>
                    </div>
                </div>
            </div>

            <!-- Recent Applications + Scholarships -->
            <div class="content-grid content-grid-60-40">

                <div class="content-card">
                    <div class="card-header-row">
                        <h4>Recent Applications</h4>
                        <a href="<?= APP_URL ?>/admin/applications" class="link-sm">
                            View all <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                    <?php if (empty($recentApplications)): ?>
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p>No applications yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="applications-table-wrap">
                            <table class="sf-table sf-table-sm">
                                <thead>
                                    <tr>
                                        <th>Applicant</th>
                                        <th>Scholarship</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentApplications as $row): ?>
                                        <tr>
                                            <td>
                                                <?= htmlspecialchars($row['applicant_name']) ?>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars(substr($row['scholarship_name'], 0, 25)) ?>…
                                            </td>
                                            <td>
                                                <span class="status-badge status-<?= $row['status'] ?>">
                                                    <?= ucfirst($row['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?= date('M j', strtotime($row['created_at'])) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="content-card">
                    <div class="card-header-row">
                        <h4>Scholarships</h4>
                        <a href="<?= APP_URL ?>/admin/scholarships" class="link-sm">
                            Manage <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                    <?php if (empty($scholarships)): ?>
                        <div class="empty-state">
                            <i class="bi bi-trophy"></i>
                            <p>No scholarships yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="schol-admin-list">
                            <?php foreach (array_slice($scholarships, 0, 5) as $s): ?>
                                <div class="schol-admin-item">
                                    <div class="schol-admin-info">
                                        <strong><?= htmlspecialchars($s['name']) ?></strong>
                                        <small>
                                            ₱<?= number_format($s['amount']) ?>
                                            &bull; <?= $s['application_count'] ?> applications
                                        </small>
                                    </div>
                                    <span class="badge-status-sm badge-<?= $s['status'] ?>">
                                        <?= ucfirst($s['status']) ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <a href="<?= APP_URL ?>/admin/scholarships/create" class="btn-add-sm">
                            <i class="bi bi-plus-lg"></i> Add New Scholarship
                        </a>
                    <?php endif; ?>
                </div>
            </div>

<!-- Quick Actions -->
            <div class="content-card">
                <h4 style="margin-bottom:1.25rem">Quick Actions</h4>
                <div class="quick-actions-grid">
                    <a href="<?= APP_URL ?>/admin/users/create" class="quick-action-card">

                        <i class="bi bi-person-plus-fill"></i>
                        <span>Add User</span>
                    </a>

                    <a href="<?= APP_URL ?>/admin/scholarships/create" class="quick-action-card">
                        <i class="bi bi-trophy-fill"></i>
                        <span>Add Scholarship</span>
                    </a>

<a href="<?= APP_URL ?>/admin/applications" class="quick-action-card">
                        <i class="bi bi-file-earmark-text-fill"></i>
                        <span>View Applications</span>
                    </a>



                    <a href="<?= APP_URL ?>/admin/users" class="quick-action-card">
                        <i class="bi bi-people-fill"></i>
                        <span>Manage Users</span>
                    </a>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require ROOT . '/app/Views/layouts/footer.php'; ?>

