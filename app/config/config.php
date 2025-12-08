<?php

// Basic app configuration

// Dynamically determine the base URL to the public folder
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$scriptName = rtrim($scriptName, '/');

// This will be something like "" or "/cinema" depending on how XAMPP is set up
define('BASE_URL', $scriptName === '/' ? '' : $scriptName);

// App name used in the layout
define('APP_NAME', 'Screenwave');

// Database configuration (update these to match your MySQL setup)
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 'onlinebooking'); // updated to match your actual database name
}
if (!defined('DB_USER')) {
    define('DB_USER', 'root'); // default XAMPP user
}
if (!defined('DB_PASS')) {
    define('DB_PASS', ''); // default XAMPP password is empty
}
if (!defined('DB_CHARSET')) {
    define('DB_CHARSET', 'utf8mb4');
}

