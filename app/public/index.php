<?php
// app/public/index.php - Front controller

session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../router/Router.php';

// Handle "Remember Me" functionality
if (empty($_SESSION['user_id']) && isset($_COOKIE['remember_token']) && isset($_COOKIE['remember_user'])) {
    require_once __DIR__ . '/../models/User.php';
    
    $userId = (int) $_COOKIE['remember_user'];
    $token = $_COOKIE['remember_token'];
    $hashedToken = hash('sha256', $token);
    
    $userModel = new User();
    $user = $userModel->findByRememberToken($userId, $hashedToken);
    
    if ($user) {
        // Auto-login the user
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
    } else {
        // Invalid token, clear cookies
        setcookie('remember_token', '', time() - 3600, '/');
        setcookie('remember_user', '', time() - 3600, '/');
    }
}

$router = new Router();

// Routes
$router->get('/', 'HomepageController@index');
$router->get('/login', 'AuthController@login');
$router->get('/signup', 'AuthController@signup');
$router->get('/profile', 'ProfileController@index');
$router->get('/logout', 'AuthController@logout');
$router->get('/forgot-password', 'AuthController@forgotPassword');
$router->get('/reset-password', 'AuthController@resetPassword');

$router->post('/profile/update', 'ProfileController@update');
$router->post('/login', 'AuthController@handleLogin');
$router->post('/signup', 'AuthController@handleSignup');
$router->post('/forgot-password', 'AuthController@handleForgotPassword');
$router->post('/reset-password', 'AuthController@handleResetPassword');

// Get the request URI - handle both direct access and rewritten URLs
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';

// If accessing root directory directly, ensure we get the correct path
if ($requestUri === '/app/public' || $requestUri === '/app/public/') {
    $requestUri = '/';
}

// Dispatch current request
$router->dispatch($requestUri, $_SERVER['REQUEST_METHOD']);