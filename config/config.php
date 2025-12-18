<?php
/**
 * Database + App configuration
 */

// APP environment
define('APP_ENV', 'development');

// Base URL
define('BASE_URL', 'http://localhost/onlinecinemabookingsystem');

// DATABASE CONFIGURATION
define('DB_HOST', 'localhost');
define('DB_NAME', 'onlinebooking');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// ====================================
// MAIL CONFIGURATION
// ====================================
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'petcopaws24@gmail.com');  // CHANGE THIS to your Gmail
define('MAIL_PASSWORD', 'dcljixrabiiiekds');     // CHANGE THIS to your App Password
define('MAIL_FROM_ADDRESS', 'petcopaws24@gmail.com'); // CHANGE THIS to your Gmail
define('MAIL_FROM_NAME', 'CineBook');

// Error reporting
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// App constants
define('APP_NAME', 'CineBook');
define('ITEMS_PER_PAGE', 12);
?>