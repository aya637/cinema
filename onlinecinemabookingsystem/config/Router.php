<?php
// config/Router.php

class Router {

    public static function handle($url) {
        
        // Define Custom Routes
        $routes = [
            ''                  => 'HomepageController@index',
            'home'              => 'HomepageController@index',
            'login'             => 'AuthController@login',
            'signup'            => 'AuthController@signup',
            'logout'            => 'AuthController@logout',
            'forgotPassword'    => 'AuthController@forgotPassword',
            'resetPassword'     => 'AuthController@resetPassword',
            'profile'           => 'ProfileController@index',
            'movies'            => 'BrowseController@index',
            'concessions'       => 'ConcessionsController@index',
            'payment'           => 'PaymentController@form',
            'admin'             => 'AdminController@dashboard',
            'admin/sales'       => 'AdminController@salesReport',
            'admin/occupancy'   => 'AdminController@occupancyReport',
            'admin/staff'       => 'StaffController@list',
            'admin/login' => 'AuthController@adminLogin',
            // Add to $routes array:
            'admin/logout' => 'AuthController@adminLogout',
            // NEW: AI Recommendation Route
            'ai/recommend'      => 'AiController@recommend' 
        ];

        // Clean the URL - remove any query strings and trim slashes
        $url = trim($url, '/');
        $url = strtok($url, '?');
        
        // Handle POST requests (Specific manual overrides)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($url === 'login') {
                self::runController('AuthController', 'handleLogin');
                return;
            } elseif ($url === 'signup') {
                self::runController('AuthController', 'handleSignup');
                return;
            } elseif ($url === 'forgotPassword') {
                self::runController('AuthController', 'handleForgotPassword');
                return;
            } elseif ($url === 'resetPassword') {
                self::runController('AuthController', 'handleResetPassword');
                return;
            } elseif ($url === 'profile/update' || $url === 'profile') {
                self::runController('ProfileController', 'update');
                return;
            }
            elseif ($url === 'admin/login') {
                self::runController('AuthController', 'handleAdminLogin');
                return;
            }
        }

        // Check for Exact Custom Match (This handles ai/recommend)
        if (array_key_exists($url, $routes)) {
            $parts = explode('@', $routes[$url]);
            self::runController($parts[0], $parts[1]);
            return;
        }

        // Dynamic Routing (Fallback)
        $parts = explode('/', $url);

        // Determine Controller
        if (!empty($parts[0])) {
            $controllerName = ucfirst($parts[0]) . 'Controller';
        } else {
            $controllerName = 'HomepageController';
        }

        // Determine Method
        $methodName = isset($parts[1]) ? $parts[1] : 'index';

        // Get Parameters
        $params = isset($parts[2]) ? array_slice($parts, 2) : [];

        // Run the Controller
        if (class_exists($controllerName)) {
            self::runController($controllerName, $methodName, $params);
        } else {
            self::error("404 - Page not found for URL: $url");
        }
    }

    // Helper to Run the Code
    private static function runController($controllerName, $methodName, $params = []) {
        // Adjust path based on your folder structure (Capital 'C' or lowercase)
        $file = __DIR__ . '/../Controllers/' . $controllerName . '.php'; 
        
        if (file_exists($file)) {
            require_once $file;
        }

        if (class_exists($controllerName)) {
            $controller = new $controllerName();
            if (method_exists($controller, $methodName)) {
                call_user_func_array([$controller, $methodName], $params);
            } else {
                self::error("Method '$methodName' not found in $controllerName");
            }
        } else {
            self::error("Controller class '$controllerName' not found.");
        }
    }

    // Error handling
    private static function error($message) {
        echo "<div style='background:#fee2e2; color:#991b1b; padding:20px; font-family:sans-serif; border:1px solid #f87171; margin:20px; border-radius:8px;'>
                <strong>System Error:</strong> $message
              </div>";
    }
}