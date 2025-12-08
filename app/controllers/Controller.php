<?php
// app/controllers/Controller.php

class Controller
{
    // Load model
    public function model(string $model)
    {
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
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        $layoutPath = __DIR__ . '/../layouts/' . $layout . '.php';

        if (!file_exists($viewPath)) {
            die("View '$view' not found.");
        }

        extract($data);

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

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

    // Get request input (GET or POST)
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
}
