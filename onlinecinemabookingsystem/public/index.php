<?php
/**
 * public/index.php
 * FIXED VERSION - Prevents duplicate class loading
 */

// Show errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define Root Path
define('ROOT_PATH', dirname(__DIR__));

// Load Database Config
if (file_exists(ROOT_PATH . '/config/config.php')) {
    require_once ROOT_PATH . '/config/config.php';
}

// Autoloader
spl_autoload_register(function ($className) {
    // Skip Router class - we load it manually
    if ($className === 'Router') {
        return;
    }
    
    $directories = [
        ROOT_PATH . '/Controllers/',   
        ROOT_PATH . '/models/',
    ];

    foreach ($directories as $directory) {
        $file = $directory . $className . '.php';
        
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Load Router (only once)
if (!class_exists('Router')) {
    require_once ROOT_PATH . '/config/Router.php';
}

// Handle "Remember Me" auto-login
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token']) && isset($_COOKIE['remember_user'])) {
    try {
        require_once ROOT_PATH . '/models/User.php';
        $userModel = new User();
        $userId = (int) $_COOKIE['remember_user'];
        $token = $_COOKIE['remember_token'];
        $hashedToken = hash('sha256', $token);
        
        $user = $userModel->findByRememberToken($userId, $hashedToken);
        
        if ($user) {
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
        } else {
            // Invalid token, clear cookies
            setcookie('remember_token', '', time() - 3600, '/');
            setcookie('remember_user', '', time() - 3600, '/');
        }
    } catch (Exception $e) {
        // Silently fail if User model doesn't have this method yet
    }
}

// Get URL
$url = isset($_GET['url']) ? $_GET['url'] : 'home';

// Run Router
Router::handle($url);