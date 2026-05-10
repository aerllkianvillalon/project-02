<?php
$isEdit     = isset($editUser);
$pageTitle  = $isEdit ? 'Edit User' : 'Add User';
$bodyClass  = 'app-body';
$formUser   = $editUser ?? ['name' => $name ?? '', 'email' => $email ?? '', 'role' => $role ?? 'student'];
?>
<?php require ROOT . '/app/Views/layouts/header.php'; ?>

<div class="app-layout">
    <?php require ROOT . '/app/Views/layouts/sidebar.php'; ?>

    <div class="app-main">
        <header class="app-topbar">
            <button class="sidebar-toggle-btn" id="sidebarOpen"><i class="bi bi-list"></i></button>
            <div class="topbar-title">
                <h2><?= $isEdit ? 'Edit User' : 'Add New User' ?></h2>
            </div>
        </header>

        <main class="app-content">
            <?php require ROOT . '/app/Views/layouts/flash.php'; ?>

            <a href="<?= APP_URL ?>/admin/users" class="back-link">
                <i class="bi bi-arrow-left"></i> Back to Users
            </a>

            <div class="form-card-wrap">
                <div class="form-card">
                    <h4 class="form-card-title">
                        <i class="bi bi-<?= $isEdit ? 'pencil-square' : 'person-plus-fill' ?>"></i>
                        <?= $isEdit ? 'Edit User Details' : 'Create New User' ?>
                    </h4>

                    <?php if (!empty($flash['error'])): ?>
                        <div class="auth-alert auth-alert-error">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <?= $flash['error'] ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST"
                          action="<?= APP_URL ?>/admin/users/<?= $isEdit ? $editUser['id'] . '/edit' : 'create' ?>">
                        <input type="hidden" name="_token" value="<?= $csrf ?>">

                        <div class="form-row-2">
                            <div class="form-group">
                                <label class="form-label">Full Name <span class="req-star">*</span></label>
                                <div class="input-with-icon">
                                    <i class="bi bi-person"></i>
                                    <input type="text" name="name" class="form-control"
                                           value="<?= htmlspecialchars($formUser['name']) ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email Address <span class="req-star">*</span></label>
                                <div class="input-with-icon">
                                    <i class="bi bi-envelope"></i>
                                    <input type="email" name="email" class="form-control"
                                           value="<?= htmlspecialchars($formUser['email']) ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label class="form-label">
                                    Password <?= $isEdit ? '<small>(leave blank to keep current)</small>' : '<span class="req-star">*</span>' ?>
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-lock"></i>
                                    <input type="password" name="password" class="form-control"
                                           placeholder="Min. 8 characters"
                                           <?= !$isEdit ? 'required minlength="8"' : '' ?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Role <span class="req-star">*</span></label>
                                <select name="role" class="form-control" required>
                                    <?php foreach (['student' => 'Student', 'reviewer' => 'Reviewer', 'admin' => 'Admin'] as $val => $label): ?>
                                        <option value="<?= $val ?>" <?= $formUser['role'] === $val ? 'selected' : '' ?>>
                                            <?= $label ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="<?= APP_URL ?>/admin/users" class="btn-cancel">Cancel</a>
                            <button type="submit" class="btn-save">
                                <i class="bi bi-<?= $isEdit ? 'check-lg' : 'plus-lg' ?>"></i>
                                <?= $isEdit ? 'Save Changes' : 'Create User' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require ROOT . '/app/Views/layouts/footer.php'; ?>