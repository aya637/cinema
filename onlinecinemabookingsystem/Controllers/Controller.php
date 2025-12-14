<?php
// app/controllers/Controller.php

class Controller
{
    // Load model
    public function model(string $model)
    {
        // FIX: Use __DIR__ (double underscore)
        $path = __DIR__ . '/../models/' . $model . '.php';
        
        if (file_exists($path)) {
            require_once $path;
            return new $model();
        }
        
        die("Model '$model' not found.");
    }

    // Load view with optional layout
    public function view(string $view, array $data = [], string $layout = 'main'): void
    {
        // FIX: Use __DIR__
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        $layoutPath = __DIR__ . '/../layouts/' . $layout . '.php';

        if (!file_exists($viewPath)) {
            die("View '$view' not found.");
        }

        extract($data);

        // Start Output Buffering
        ob_start();
        require $viewPath;
        $content = ob_get_clean(); // Store view content in variable

        // Load Layout if it exists, otherwise just echo content
        if ($layout && file_exists($layoutPath)) {
            require $layoutPath;
        } else {
            echo $content;
        }
    }

    // Redirect
    public function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }

    // Get request input
    public function request(string $key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    // JSON response
    public function json($data, int $status = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    // Middleware (From Snippet 1 - kept because it is useful)
    public function middleware(callable $callback): void
    {
        if (!$callback()) {
            $this->redirect('/login');
        }
    }
}