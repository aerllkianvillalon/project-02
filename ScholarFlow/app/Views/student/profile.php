<?php
/**
 * Student Profile
 *
 * @var array $auth     Current user session data
 * @var array $profile  Full user row from DB
 * @var string $csrf    CSRF token
 * @var array  $flash   Flash messages
 */
$pageTitle = 'My Profile';
$bodyClass  = 'app-body';
?>
<?php require ROOT . '/app/Views/layouts/header.php'; ?>

<div class="app-layout">
    <?php require ROOT . '/app/Views/layouts/sidebar.php'; ?>

    <div class="app-main">
        <header class="app-topbar">
            <button class="sidebar-toggle-btn" id="sidebarOpen"><i class="bi bi-list"></i></button>
            <div class="topbar-title">
                <h2>My Profile</h2>
                <span>Manage your personal information</span>
            </div>
        </header>

        <main class="app-content">
            <?php require ROOT . '/app/Views/layouts/flash.php'; ?>

            <div class="profile-layout">
                <!-- Avatar Card -->
                <div class="profile-avatar-card">
                    <div class="avatar-display">
                        <?php if (!empty($profile['avatar'])): ?>
                            <img src="<?= APP_URL . '/uploads/' . htmlspecialchars($profile['avatar']) ?>"
                                 alt="Avatar" id="avatarPreview">
                        <?php else: ?>
                            <div class="avatar-initials" id="avatarInitials">
                                <?= strtoupper(substr($profile['name'], 0, 2)) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="avatar-info">
                        <h4><?= htmlspecialchars($profile['name']) ?></h4>
                        <span class="badge-role badge-student">Student</span>
                        <p><?= htmlspecialchars($profile['email']) ?></p>
                    </div>
                    <label for="avatar" class="avatar-upload-btn">
                        <i class="bi bi-camera"></i> Change Photo
                    </label>
                </div>

                <!-- Profile Form -->
                <div class="profile-form-card">
                    <form method="POST" action="<?= APP_URL ?>/profile"
                          enctype="multipart/form-data" class="profile-form">
                        <input type="hidden" name="_token" value="<?= $csrf ?>">
                        <input type="file" name="avatar" id="avatar" accept="image/*"
                               class="d-none" onchange="previewAvatar(this)">

                        <h5 class="form-section-title">Personal Information</h5>
                        <div class="form-row-2">
                            <div class="form-group">
                                <label class="form-label">
                                    Full Name <span class="req-star">*</span>
                                </label>
                                <input type="text" name="name" class="form-control"
                                       value="<?= htmlspecialchars($profile['name']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone Number</label>
                                <div class="input-with-icon">
                                    <i class="bi bi-telephone"></i>
                                    <input type="text" name="phone" class="form-control"
                                           value="<?= htmlspecialchars($profile['phone'] ?? '') ?>"
                                           placeholder="+63 9XX XXX XXXX">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Home Address</label>
                            <div class="input-with-icon">
                                <i class="bi bi-geo-alt"></i>
                                <input type="text" name="address" class="form-control"
                                       value="<?= htmlspecialchars($profile['address'] ?? '') ?>"
                                       placeholder="Street, City, Province">
                            </div>
                        </div>

                        <h5 class="form-section-title" style="margin-top:2rem">
                            Academic Information
                        </h5>
                        <div class="form-row-2">
                            <div class="form-group">
                                <label class="form-label">School / University</label>
                                <div class="input-with-icon">
                                    <i class="bi bi-building"></i>
                                    <input type="text" name="school" class="form-control"
                                           value="<?= htmlspecialchars($profile['school'] ?? '') ?>"
                                           placeholder="Your school name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Course / Program</label>
                                <div class="input-with-icon">
                                    <i class="bi bi-book"></i>
                                    <input type="text" name="course" class="form-control"
                                           value="<?= htmlspecialchars($profile['course'] ?? '') ?>"
                                           placeholder="e.g. BS Computer Science">
                                </div>
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label class="form-label">GPA / Grade</label>
                                <input type="text" name="gpa" class="form-control"
                                       value="<?= htmlspecialchars($profile['gpa'] ?? '') ?>"
                                       placeholder="e.g. 1.50 or 95.5%">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Year Level</label>
                                <select name="year_level" class="form-control">
                                    <option value="">Select year…</option>
                                    <?php
                                    $yearLevels = ['1st Year','2nd Year','3rd Year',
                                                   '4th Year','5th Year','Graduate'];
                                    foreach ($yearLevels as $yr):
                                    ?>
                                        <option value="<?= $yr ?>"
                                            <?= ($profile['year_level'] ?? '') === $yr
                                                    ? 'selected' : '' ?>>
                                            <?= $yr ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-save">
                                <i class="bi bi-check-lg"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview  = document.getElementById('avatarPreview')
                          || document.getElementById('avatarInitials');
            if (!preview) return;
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                const img  = document.createElement('img');
                img.src    = e.target.result;
                img.id     = 'avatarPreview';
                preview.replaceWith(img);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require ROOT . '/app/Views/layouts/footer.php'; ?>