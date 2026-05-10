<?php $pageTitle = 'Create Account'; $bodyClass = 'auth-body'; ?>
<?php require ROOT . '/app/Views/layouts/header.php'; ?>

<div class="auth-wrapper">
    <div class="auth-card auth-card-wide">
        <div class="auth-brand">
            <div class="auth-brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
            <h1 class="auth-brand-name">Scholar<strong>Flow</strong></h1>
            <p class="auth-brand-tagline">Start your scholarship journey today</p>
        </div>

        <?php if (!empty($flash['error'])): ?>
            <div class="auth-alert auth-alert-error">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <?= $flash['error'] ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= APP_URL ?>/register" class="auth-form" novalidate>
            <input type="hidden" name="_token" value="<?= $csrf ?>">

            <div class="form-row-2">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <div class="input-with-icon">
                        <i class="bi bi-person"></i>
                        <input type="text" name="name" class="form-control"
                               value="<?= htmlspecialchars($name ?? '') ?>"
                               placeholder="Juan dela Cruz" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="input-with-icon">
                        <i class="bi bi-envelope"></i>
                        <input type="email" name="email" class="form-control"
                               value="<?= htmlspecialchars($email ?? '') ?>"
                               placeholder="juan@example.com" required>
                    </div>
                </div>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-with-icon">
                        <i class="bi bi-lock"></i>
                        <input type="password" name="password" class="form-control"
                               placeholder="Min. 8 characters" required minlength="8">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <div class="input-with-icon">
                        <i class="bi bi-lock-fill"></i>
                        <input type="password" name="password_confirmation" class="form-control"
                               placeholder="Repeat password" required>
                    </div>
                </div>
            </div>

            <div class="form-check-terms">
                <input class="form-check-input" type="checkbox" id="termsCheck" required>
                <label class="form-check-label" for="termsCheck">
                    I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                </label>
            </div>

            <button type="submit" class="btn-auth">
                <span>Create Account</span>
                <i class="bi bi-arrow-right"></i>
            </button>
        </form>

        <p class="auth-footer-text">
            Already have an account?
            <a href="<?= APP_URL ?>/login">Sign in instead</a>
        </p>
    </div>

    <div class="auth-decoration">
        <div class="deco-shape deco-1"></div>
        <div class="deco-shape deco-2"></div>
        <div class="deco-shape deco-3"></div>
    </div>
</div>

<?php require ROOT . '/app/Views/layouts/footer.php'; ?>