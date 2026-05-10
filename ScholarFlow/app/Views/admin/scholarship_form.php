<?php
$isEdit    = isset($scholarship);
$pageTitle = $isEdit ? 'Edit Scholarship' : 'Add Scholarship';
$bodyClass = 'app-body';
$vals      = $input ?? $scholarship ?? [];
?>
<?php require ROOT . '/app/Views/layouts/header.php'; ?>

<div class="app-layout">
    <?php require ROOT . '/app/Views/layouts/sidebar.php'; ?>

    <div class="app-main">
        <header class="app-topbar">
            <button class="sidebar-toggle-btn" id="sidebarOpen"><i class="bi bi-list"></i></button>
            <div class="topbar-title">
                <h2><?= $isEdit ? 'Edit Scholarship' : 'Add Scholarship' ?></h2>
            </div>
        </header>

        <main class="app-content">
            <?php require ROOT . '/app/Views/layouts/flash.php'; ?>

            <a href="<?= APP_URL ?>/admin/scholarships" class="back-link">
                <i class="bi bi-arrow-left"></i> Back to Scholarships
            </a>

            <div class="form-card-wrap">
                <div class="form-card form-card-lg">
                    <h4 class="form-card-title">
                        <i class="bi bi-trophy-fill"></i>
                        <?= $isEdit ? 'Edit Scholarship Details' : 'Create New Scholarship' ?>
                    </h4>

                    <?php if (!empty($flash['error'])): ?>
                        <div class="auth-alert auth-alert-error">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <?= $flash['error'] ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST"
                          action="<?= APP_URL ?>/admin/scholarships/<?= $isEdit ? $scholarship['id'] . '/edit' : 'create' ?>">
                        <input type="hidden" name="_token" value="<?= $csrf ?>">

                        <div class="form-group">
                            <label class="form-label">Scholarship Name <span class="req-star">*</span></label>
                            <input type="text" name="name" class="form-control"
                                   value="<?= htmlspecialchars($vals['name'] ?? '') ?>"
                                   placeholder="e.g. DOST Excellence Scholarship" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Description <span class="req-star">*</span></label>
                            <textarea name="description" class="form-control" rows="4"
                                      placeholder="Describe the scholarship, its goals, and who it's for..." required><?= htmlspecialchars($vals['description'] ?? '') ?></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Requirements</label>
                            <textarea name="requirements" class="form-control" rows="3"
                                      placeholder="List eligibility requirements..."><?= htmlspecialchars($vals['requirements'] ?? '') ?></textarea>
                        </div>

                        <div class="form-row-3">
                            <div class="form-group">
                                <label class="form-label">Award Amount (₱) <span class="req-star">*</span></label>
                                <div class="input-with-icon">
                                    <i class="bi bi-currency-exchange"></i>
                                    <input type="number" name="amount" class="form-control"
                                           value="<?= htmlspecialchars($vals['amount'] ?? '') ?>"
                                           placeholder="5000.00" step="0.01" min="1" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Deadline <span class="req-star">*</span></label>
                                <div class="input-with-icon">
                                    <i class="bi bi-calendar3"></i>
                                    <input type="date" name="deadline" class="form-control"
                                           value="<?= htmlspecialchars($vals['deadline'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Slots Available</label>
                                <div class="input-with-icon">
                                    <i class="bi bi-people"></i>
                                    <input type="number" name="slots" class="form-control"
                                           value="<?= htmlspecialchars($vals['slots'] ?? '') ?>"
                                           placeholder="Leave blank for unlimited" min="1">
                                </div>
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <?php foreach (['active' => 'Active', 'inactive' => 'Inactive', 'closed' => 'Closed'] as $val => $label): ?>
                                        <option value="<?= $val ?>"
                                            <?= ($vals['status'] ?? 'active') === $val ? 'selected' : '' ?>>
                                            <?= $label ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Application Type</label>
                                <div class="toggle-option-group">
                                    <label class="toggle-option">
                                        <input type="radio" name="allows_multiple" value="0"
                                               <?= empty($vals['allows_multiple']) ? 'checked' : '' ?>>
                                        <span class="toggle-option-label">
                                            <i class="bi bi-star-fill"></i>
                                            <strong>Exclusive</strong>
                                            <small>Conflicts with other exclusive scholarships</small>
                                        </span>
                                    </label>
                                    <label class="toggle-option">
                                        <input type="radio" name="allows_multiple" value="1"
                                               <?= !empty($vals['allows_multiple']) ? 'checked' : '' ?>>
                                        <span class="toggle-option-label">
                                            <i class="bi bi-infinity"></i>
                                            <strong>Open / Multiple</strong>
                                            <small>Students can apply to other scholarships</small>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="<?= APP_URL ?>/admin/scholarships" class="btn-cancel">Cancel</a>
                            <button type="submit" class="btn-save">
                                <i class="bi bi-<?= $isEdit ? 'check-lg' : 'plus-lg' ?>"></i>
                                <?= $isEdit ? 'Save Changes' : 'Create Scholarship' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require ROOT . '/app/Views/layouts/footer.php'; ?>