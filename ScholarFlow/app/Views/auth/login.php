<?php $pageTitle = 'Sign In'; $bodyClass = 'auth-body'; ?>
<?php require ROOT . '/app/Views/layouts/header.php'; ?>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-brand">
            <div class="auth-brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
            <h1 class="auth-brand-name">Scholar<strong>Flow</strong></h1>
            <p class="auth-brand-tagline">Your gateway to educational funding</p>
        </div>

        <?php if (!empty($flash['error'])): ?>
            <div class="auth-alert auth-alert-error">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <?= htmlspecialchars($flash['error']) ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($flash['success'])): ?>
            <div class="auth-alert auth-alert-success">
                <i class="bi bi-check-circle-fill"></i>
                <?= htmlspecialchars($flash['success']) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= APP_URL ?>/login" class="auth-form" novalidate>
            <input type="hidden" name="_token" value="<?= $csrf ?>">

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-with-icon">
                    <i class="bi bi-envelope"></i>
                    <input type="email" name="email" class="form-control"
                           value="<?= htmlspecialchars($email ?? '') ?>"
                           placeholder="you@example.com" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Password
                    <a href="#" class="label-link">Forgot password?</a>
                </label>
                <div class="input-with-icon input-password">
                    <i class="bi bi-lock"></i>
                    <input type="password" name="password" class="form-control"
                           placeholder="••••••••" required id="passwordInput">
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-auth">
                <span>Sign In</span>
                <i class="bi bi-arrow-right"></i>
            </button>
        </form>

        <p class="auth-footer-text">
            Don't have an account?
            <a href="<?= APP_URL ?>/register">Create one free</a>
        </p>
    </div>

    <div class="auth-decoration">
        <div class="deco-shape deco-1"></div>
        <div class="deco-shape deco-2"></div>
        <div class="deco-shape deco-3"></div>
        <div class="deco-stats">
            <div class="deco-stat">
                <span class="stat-num">500+</span>
                <span class="stat-label">Scholarships</span>
            </div>
            <div class="deco-stat">
                <span class="stat-num">₱2M+</span>
                <span class="stat-label">Awarded</span>
            </div>
            <div class="deco-stat">
                <span class="stat-num">1,200+</span>
                <span class="stat-label">Students</span>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const inp = document.getElementById('passwordInput');
    const ico = document.getElementById('eyeIcon');
    if (inp.type === 'password') {
        inp.type = 'text';
        ico.className = 'bi bi-eye-slash';
    } else {
        inp.type = 'password';
        ico.className = 'bi bi-eye';
    }
}
</script>

<?php require ROOT . '/app/Views/layouts/footer.php'; ?>