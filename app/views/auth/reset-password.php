<section class="auth-page">
    <div class="cb-container">
        <div class="auth-card">
            <h2>Reset Password</h2>
            <p class="auth-subtitle">Enter your new password below.</p>
            
            <?php if (!empty($errors)): ?>
                <div class="auth-alert auth-error">
                    <?php foreach ($errors as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
                <div style="display: flex; gap: 0.75rem; margin-top: 1rem;">
                    <a href="<?= BASE_URL ?>/forgot-password" class="btn btn-outline full-width">Request New Link</a>
                    <a href="<?= BASE_URL ?>/login" class="btn btn-ghost full-width">Back to Login</a>
                </div>
            <?php else: ?>
                <form class="auth-form" method="post" action="<?= BASE_URL ?>/reset-password">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">
                    
                    <?php if (!empty($email)): ?>
                        <div class="auth-field">
                            <label>Email</label>
                            <input type="email" value="<?= htmlspecialchars($email) ?>" disabled style="opacity: 0.6;">
                        </div>
                    <?php endif; ?>
                    
                    <div class="auth-field">
                        <label for="reset-password">New Password</label>
                        <input
                            id="reset-password"
                            name="password"
                            type="password"
                            placeholder="Enter new password (min. 8 characters)"
                            required
                            autofocus
                        >
                    </div>
                    
                    <div class="auth-field">
                        <label for="reset-confirm">Confirm New Password</label>
                        <input
                            id="reset-confirm"
                            name="password_confirmation"
                            type="password"
                            placeholder="Repeat new password"
                            required
                        >
                    </div>
                    
                    <button type="submit" class="btn btn-primary full-width">Reset Password</button>
                </form>
                
                <p class="auth-switch">
                    Remember your password?
                    <a href="<?= BASE_URL ?>/login" class="auth-link">Sign in</a>
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>