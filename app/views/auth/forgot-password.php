<section class="auth-page">
    <div class="cb-container">
        <div class="auth-card">
            <h2>Forgot Password</h2>
            <p class="auth-subtitle">Enter your email address and we'll send you a link to reset your password.</p>
            
            <?php if (!empty($success)): ?>
                <div class="auth-alert" style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.5); color: #bbf7d0;">
                    <p><?= htmlspecialchars($message) ?></p>
                </div>
                <a href="<?= BASE_URL ?>/login" class="btn btn-primary full-width">Back to Sign In</a>
            <?php else: ?>
                <?php if (!empty($errors)): ?>
                    <div class="auth-alert auth-error">
                        <?php foreach ($errors as $error): ?>
                            <p><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <form class="auth-form" method="post" action="<?= BASE_URL ?>/forgot-password">
                    <div class="auth-field">
                        <label for="forgot-email">Email Address</label>
                        <input
                            id="forgot-email"
                            name="email"
                            type="email"
                            placeholder="you@example.com"
                            required
                            autofocus
                        >
                    </div>
                    <button type="submit" class="btn btn-primary full-width">Send Reset Link</button>
                </form>
                
                <p class="auth-switch">
                    Remember your password?
                    <a href="<?= BASE_URL ?>/login" class="auth-link">Sign in</a>
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>