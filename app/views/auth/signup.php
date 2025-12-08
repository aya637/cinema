<section class="auth-page">
    <div class="cb-container">
        <div class="auth-card">
            <h2>Create Account</h2>
            <p class="auth-subtitle">Join <?= APP_NAME ?> and start booking your favorite movies in seconds.</p>
            <?php if (!empty($errors)): ?>
                <div class="auth-alert auth-error">
                    <?php foreach ($errors as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form class="auth-form" method="post" action="<?= BASE_URL ?>/signup">
                <div class="auth-field">
                    <label for="signup-name">Full Name</label>
                    <input
                        id="signup-name"
                        name="name"
                        type="text"
                        placeholder="Alex Johnson"
                        value="<?= isset($oldName) ? htmlspecialchars($oldName) : '' ?>"
                        required
                    >
                </div>
                <div class="auth-field">
                    <label for="signup-email">Email</label>
                    <input
                        id="signup-email"
                        name="email"
                        type="email"
                        placeholder="you@example.com"
                        value="<?= isset($oldEmail) ? htmlspecialchars($oldEmail) : '' ?>"
                        required
                    >
                </div>
                <div class="auth-field">
                    <label for="signup-password">Password</label>
                    <input id="signup-password" name="password" type="password" placeholder="Create a strong password" required>
                </div>
                <div class="auth-field">
                    <label for="signup-confirm">Confirm Password</label>
                    <input id="signup-confirm" name="password_confirmation" type="password" placeholder="Repeat your password" required>
                </div>
                <button type="submit" class="btn btn-primary full-width">Create Account</button>
            </form>
            <p class="auth-switch">
                Already have an account?
                <a href="<?= BASE_URL ?>/login" class="auth-link">Sign in</a>
            </p>
        </div>
    </div>
</section>


