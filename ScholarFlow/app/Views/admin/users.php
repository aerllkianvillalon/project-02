<?php
/**
 * Admin — Users
 *
 * @var array        $auth   Current user (admin)
 * @var array[]      $users  Users list
 * @var string       $role   Optional role filter
 * @var array        $flash  Flash messages
 */
$pageTitle = 'Admin — Users';
$bodyClass = 'app-body';
?>

<?php require ROOT . '/app/Views/layouts/header.php'; ?>

<div class="app-layout">
    <?php require ROOT . '/app/Views/layouts/sidebar.php'; ?>

    <div class="app-main">
        <header class="app-topbar">
            <button class="sidebar-toggle-btn" id="sidebarOpen"><i class="bi bi-list"></i></button>
            <div class="topbar-title">
                <h2>Users</h2>
                <span>Manage accounts</span>
            </div>
        </header>

        <main class="app-content">
            <?php require ROOT . '/app/Views/layouts/flash.php'; ?>

            <div class="content-card">
                <div class="card-header-row">
                    <h4>All Users</h4>
                    <a href="<?= APP_URL ?>/admin/users/create" class="btn-add-sm">
                        <i class="bi bi-plus-lg"></i> Add User
                    </a>
                </div>

                <div class="filter-bar">
                    <div class="filter-search">
                        <i class="bi bi-search"></i>
                        <form method="GET" action="<?= APP_URL ?>/admin/users" style="display:flex; align-items:center; gap:0.75rem; width:100%;">
                            <input
                                type="text"
                                name="q"
                                value="<?= htmlspecialchars($q ?? '') ?>"
                                placeholder="Search by name or email..."
                                class="search-input"
                            >

                            <select name="role" class="form-select form-select-sm" style="max-width:160px;">
                                <option value="" <?= empty($role) ? 'selected' : '' ?>>All</option>
                                <option value="student" <?= ($role ?? '') === 'student' ? 'selected' : '' ?>>Students</option>
                                <option value="reviewer" <?= ($role ?? '') === 'reviewer' ? 'selected' : '' ?>>Reviewers</option>
                                <option value="admin" <?= ($role ?? '') === 'admin' ? 'selected' : '' ?>>Admins</option>
                            </select>

                            <button type="submit" class="btn btn-sm" style="white-space:nowrap;">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <?php if (empty($users)): ?>
                    <div class="empty-state">
                        <i class="bi bi-people"></i>
                        <p>No users found.</p>
                    </div>
                <?php else: ?>
                    <div class="applications-table-wrap">
                        <table class="sf-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Created</th>
                                    <th style="width: 240px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $u): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($u['name'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($u['email'] ?? '') ?></td>
                                        <td>
                                            <span class="badge-status-sm badge-status-<?= htmlspecialchars($u['role'] ?? '') ?>">
                                                <?= htmlspecialchars(ucfirst($u['role'] ?? '')) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?= !empty($u['created_at'])
                                                ? htmlspecialchars(date('M j, Y', strtotime($u['created_at'])))
                                                : '-' ?>
                                        </td>
                                        <td>
                                            <div class="action-btns">
                                                <a class="btn-icon-action" href="<?= APP_URL ?>/admin/users/<?= (int)($u['id'] ?? 0) ?>/edit" title="Edit">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>

                                                <form method="POST"
                                                      action="<?= APP_URL ?>/admin/users/<?= (int)($u['id'] ?? 0) ?>/delete"
                                                      onsubmit="return confirm('Delete this user?')"
                                                      style="display:inline;">
                                                    <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf ?? '') ?>">
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
            </div>
        </main>
    </div>
</div>

<?php require ROOT . '/app/Views/layouts/footer.php'; ?>

