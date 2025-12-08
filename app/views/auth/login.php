<section class="auth-page">
    <div class="cb-container">
        <div class="auth-card">
            <h2>Sign In</h2>
            <p class="auth-subtitle">Welcome back to <?= APP_NAME ?>. Sign in to manage your bookings.</p>
            
            <?php if (!empty($_SESSION['password_reset_success'])): ?>
                <div class="auth-alert" style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.5); color: #bbf7d0;">
                    <p>✓ Password reset successful! Please sign in with your new password.</p>
                </div>
                <?php unset($_SESSION['password_reset_success']); ?>
            <?php endif; ?>
            
            <?php if (!empty($errors)): ?>
                <div class="auth-alert auth-error">
                    <?php foreach ($errors as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form class="auth-form" method="post" action="<?= BASE_URL ?>/login">
                <div class="auth-field">
                    <label for="login-email">Email</label>
                    <input
                        id="login-email"
                        name="email"
                        type="email"
                        placeholder="you@example.com"
                        value="<?= isset($oldEmail) ? htmlspecialchars($oldEmail) : '' ?>"
                        required
                    >
                </div>
                <div class="auth-field">
                    <label for="login-password">Password</label>
                    <input id="login-password" name="password" type="password" placeholder="••••••••" required>
                </div>
                <div class="auth-meta-row">
                    <label class="auth-remember">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="<?= BASE_URL ?>/forgot-password" class="auth-link">Forgot password?</a>
                </div>
                <button type="submit" class="btn btn-primary full-width">Sign In</button>
            </form>
            <p class="auth-switch">
                New to <?= APP_NAME ?>?
                <a href="<?= BASE_URL ?>/signup" class="auth-link">Create an account</a>
            </p>
        </div>
    </div>
</section>

