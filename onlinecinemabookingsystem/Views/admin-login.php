<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login - ScreenWave</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/onlinecinemabookingsystem/public/css/admin-dashboard.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-card {
            background: #111827; /* Gray 900 */
            border: 1px solid #374151;
            padding: 2.5rem;
            border-radius: 1rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .logo-area {
            text-align: center;
            margin-bottom: 2rem;
        }
        .logo-text {
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
        }
        .logo-text span { color: #6366f1; }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="logo-area">
            <div class="logo-text"><span>Screen</span>Wave</div>
            <p style="color: #9ca3af; margin-top: 5px;">Staff Access Portal</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div style="background: rgba(239, 68, 68, 0.2); color: #fca5a5; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 0.9rem; text-align: center;">
                Invalid email or password.
            </div>
        <?php endif; ?>

        <form action="/onlinecinemabookingsystem/public/index.php?url=admin/login" method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="admin@cinebook.com">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn primary" style="width: 100%; margin-top: 10px;">
                Secure Login
            </button>
        </form>

        <div style="margin-top: 20px; text-align: center;">
            <a href="/onlinecinemabookingsystem/public/" style="color: #6366f1; text-decoration: none; font-size: 0.9rem;">
                &larr; Back to Website
            </a>
        </div>
    </div>

</body>
</html>