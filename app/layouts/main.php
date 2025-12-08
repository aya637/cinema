<?php
// app/layouts/main.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | ' : '' ?><?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="page-root">
        <header class="cb-header">
            <div class="cb-container cb-header-inner">
                <a href="<?= BASE_URL ?>/" class="cb-logo">
                    <img src="<?= BASE_URL ?>/images/WhatsApp_Image_2025-12-06_at_23.18.23_0e2a60d9-removebg-preview.png" alt="<?= APP_NAME ?> logo" class="cb-logo-icon">
                    <span class="cb-logo-text"><span class="accent">Screen</span>wave</span>
                </a>
                <nav class="cb-nav">
                    <a href="<?= BASE_URL ?>/" class="cb-nav-link">Movies</a>
                    <a href="#" class="cb-nav-link">Showtimes</a>
                    <a href="#" class="cb-nav-link">Contact</a>
                </nav>
                <div class="cb-auth-buttons">
                    <?php if (!empty($_SESSION['user_id'])): ?>
                        <a href="<?= BASE_URL ?>/profile" class="btn btn-ghost">
                            <?= htmlspecialchars($_SESSION['user_name'] ?? 'Profile') ?>
                        </a>
                        <a href="<?= BASE_URL ?>/logout" class="btn btn-primary">Logout</a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/login" class="btn btn-ghost">Sign In</a>
                        <a href="<?= BASE_URL ?>/signup" class="btn btn-primary">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <main>
            <?= $content ?? '' ?>
        </main>

        <footer class="cb-footer">
            <div class="cb-container cb-footer-inner">
                <div class="cb-footer-section">
                    <div class="cb-logo footer-logo">
                        <img src="<?= BASE_URL ?>/images/WhatsApp_Image_2025-12-06_at_23.18.23_0e2a60d9-removebg-preview.png" alt="<?= APP_NAME ?> logo" class="cb-logo-icon">
                        <span class="cb-logo-text"><span class="accent">Screen</span>wave</span>
                    </div>
                    <p class="cb-footer-text">
                        Your premier destination for the latest movies and unforgettable cinema experiences.
                    </p>
                </div>
                <div class="cb-footer-columns">
                    <div>
                        <h4 class="cb-footer-heading">Quick Links</h4>
                        <ul>
                            <li><a href="#">Now Showing</a></li>
                            <li><a href="#">Coming Soon</a></li>
                            <li><a href="#">Gift Cards</a></li>
                            <li><a href="#">Membership</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="cb-footer-heading">Support</h4>
                        <ul>
                            <li><a href="#">Help Center</a></li>
                            <li><a href="#">Contact Us</a></li>
                            <li><a href="#">Refund Policy</a></li>
                            <li><a href="#">Accessibility</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="cb-footer-heading">Connect</h4>
                        <div class="cb-social-row">
                            <a href="#" class="cb-social-link" aria-label="Facebook">
                                <img src="<?= BASE_URL ?>/images/facebook.png" alt="Facebook">
                            </a>
                            <a href="#" class="cb-social-link" aria-label="Instagram">
                                <img src="<?= BASE_URL ?>/images/instgram.png" alt="Instagram">
                            </a>
                            <a href="#" class="cb-social-link" aria-label="TikTok">
                                <img src="<?= BASE_URL ?>/images/tiktok.png" alt="TikTok">
                            </a>
                            <a href="#" class="cb-social-link" aria-label="YouTube">
                                <img src="<?= BASE_URL ?>/images/youtube.png" alt="YouTube">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cb-footer-bottom">
                <div class="cb-container">
                    <p>© 2024 Screenwave. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>


