<?php
/**
 * Reviewer — All Applications List
 *
 * @var array   $auth          Current user
 * @var array[] $applications  Applications with joined scholarship + user data
 * @var string  $status        Active status filter ('' | 'pending' | 'approved' | 'rejected')
 * @var array   $flash         Flash messages
 */
$pageTitle = 'All Applications';
$bodyClass  = 'app-body';
?>
<?php require ROOT . '/app/Views/layouts/header.php'; ?>

<div class="app-layout">
    <?php require ROOT . '/app/Views/layouts/sidebar.php'; ?>

    <div class="app-main">
        <header class="app-topbar">
            <button class="sidebar-toggle-btn" id="sidebarOpen"><i class="bi bi-list"></i></button>
            <div class="topbar-title">
                <h2>All Applications</h2>
                <span>Review and manage applications</span>
            </div>
        </header>

        <main class="app-content">
            <?php require ROOT . '/app/Views/layouts/flash.php'; ?>

            <!-- Status Tabs -->
            <div class="tab-filter-bar">
                <?php
                $tabs = [
                    ''         => 'All',
                    'pending'  => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ];
                foreach ($tabs as $val => $label):
                    $isActive = ($status === $val);
                ?>
                    <a href="<?= APP_URL ?>/reviewer/applications<?= $val ? '?status=' . $val : '' ?>"
                       class="tab-filter <?= $isActive ? 'active' : '' ?>">
                        <?php if ($val): ?>
                            <span class="dot dot-<?= $val ?>"></span>
                        <?php endif; ?>
                        <?= $label ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <?php if (empty($applications)): ?>
                <div class="empty-state empty-state-lg">
                    <div class="empty-icon"><i class="bi bi-inbox-fill"></i></div>
                    <h4>No Applications Found</h4>
                    <p>There are no applications matching the selected filter.</p>
                </div>
            <?php else: ?>
                <div class="applications-table-wrap">
                    <table class="sf-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Applicant</th>
                                <th>Scholarship</th>
                                <th>Applied</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applications as $row): ?>
                                <tr>
                                    <td class="mono">
                                        <?= str_pad($row['id'], 5, '0', STR_PAD_LEFT) ?>
                                    </td>
                                    <td>
                                        <div class="applicant-cell">
                                            <div class="mini-avatar">
                                                <?= strtoupper(
                                                    substr($row['applicant_name'], 0, 2)
                                                ) ?>
                                            </div>
                                            <div>
                                                <strong>
                                                    <?= htmlspecialchars($row['applicant_name']) ?>
                                                </strong>
                                                <small>
                                                    <?= htmlspecialchars($row['email']) ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($row['scholarship_name']) ?></td>
                                    <td><?= date('M j, Y', strtotime($row['created_at'])) ?></td>
                                    <td>
                                        <span class="status-badge status-<?= $row['status'] ?>">
                                            <span class="status-dot"></span>
                                            <?= ucfirst($row['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= APP_URL ?>/reviewer/applications/<?= $row['id'] ?>"
                                           class="btn-table-action">
                                            <?= $row['status'] === 'pending' ? 'Review' : 'View' ?>
                                            <i class="bi bi-arrow-right"></i>
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

<?php require ROOT . '/app/Views/layouts/footer.php'; ?>