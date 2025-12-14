<section class="auth-page">
    <style>
        .auth-page {
            position: relative;
            opacity: 0;
            animation: page-fade 640ms cubic-bezier(0.22, 0.61, 0.36, 1) forwards;
        }
        .auth-page.fade-out {
            animation: page-fade-out 320ms ease forwards;
        }
        .auth-notice {
            position: relative;
        }
        @keyframes page-fade {
            0% { opacity: 0; transform: translateY(10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes page-fade-out {
            0% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(10px); }
        }
        .auth-card {
            position: relative;
        }
        .auth-box-close {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(0,0,0,0.35);
            color: #e5e7eb;
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 999px;
            padding: 6px 10px;
            font-weight: 600;
            cursor: pointer;
            backdrop-filter: blur(6px);
            transition: transform 160ms ease, background 160ms ease, opacity 160ms ease;
        }
        .auth-box-close:hover {
            background: rgba(0,0,0,0.5);
            transform: translateY(-1px);
        }
    </style>
    <div class="cb-container">
        <div class="auth-card">
            <button id="auth-box-close" class="auth-box-close" aria-label="Close sign in">×</button>
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

            <?php if (!empty($notice)): ?>
                <div class="auth-alert auth-notice" style="background: rgba(79, 70, 229, 0.12); border: 1px solid rgba(99, 102, 241, 0.35); color: #c7d2fe;">
                    <p><?= htmlspecialchars($notice) ?></p>
                </div>
            <?php endif; ?>
            
            <form class="auth-form" method="post" action="<?= BASE_URL ?>/public/index.php?url=login">
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
                    <a href="<?= BASE_URL ?>/public/index.php?url=forgotPassword" class="auth-link">Forgot password?</a>
                </div>
                <button type="submit" class="btn btn-primary full-width">Sign In</button>
            </form>
            <p class="auth-switch">
                New to <?= APP_NAME ?>?
                <a href="<?= BASE_URL ?>/public/index.php?url=signup" class="auth-link">Create an account</a>
            </p>
        </div>
    </div>
</section>

<script>
    (function() {
        const page = document.querySelector('.auth-page');
        const closeBtn = document.getElementById('auth-box-close');
        const fallback = '<?= BASE_URL ?>/public/';
        if (closeBtn && page) {
            closeBtn.addEventListener('click', (e) => {
                e.preventDefault();
                page.classList.add('fade-out');
                setTimeout(() => {
                    if (window.history.length > 1) {
                        window.history.back();
                    } else {
                        window.location.href = fallback;
                    }
                }, 260);
            });
        }
    })();
</script>