<section class="auth-page">
    <style>
        .auth-page {
            position: relative;
            opacity: 0;
            animation: page-fade 640ms cubic-bezier(0.22, 0.61, 0.36, 1) forwards;
        }
        .auth-notice {
            position: relative;
        }
        @keyframes page-fade {
            0% { opacity: 0; transform: translateY(10px); }
            100% { opacity: 1; transform: translateY(0); }
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
            <button id="auth-box-close" class="auth-box-close" aria-label="Close sign up">×</button>
            <h2>Create Account</h2>
            <p class="auth-subtitle">Join <?= APP_NAME ?> and start booking your favorite movies in seconds.</p>
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
            <form class="auth-form" method="post" action="<?= BASE_URL ?>/public/index.php?url=signup">
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
                <a href="<?= BASE_URL ?>/public/index.php?url=login" class="auth-link">Sign in</a>
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
                page.style.animation = 'page-fade 640ms cubic-bezier(0.22, 0.61, 0.36, 1) forwards'; // ensure initial state
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