<?php

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $path, string $action): void
    {
        $this->routes['GET'][$this->normalizePath($path)] = $action;
    }

    public function post(string $path, string $action): void
    {
        $this->routes['POST'][$this->normalizePath($path)] = $action;
    }

    public function dispatch(string $requestUri, string $requestMethod = 'GET'): void
    {
        $path = parse_url($requestUri, PHP_URL_PATH) ?? '/';
        
        // Handle the case where we're accessing the root directory
        if ($path === '/app/public' || $path === '/app/public/') {
            $path = '/';
        }
        
        $path = $this->stripBasePath($path);
        $path = $this->normalizePath($path);
        $method = strtoupper($requestMethod);

        $action = $this->routes[$method][$path] ?? null;

        if (!$action) {
            http_response_code(404);
            echo "404 - Page not found<br>";
            echo "Requested path: " . htmlspecialchars($path) . "<br>";
            echo "Available routes: " . implode(', ', array_keys($this->routes[$method]));
            return;
        }

        [$controllerName, $methodName] = explode('@', $action);

        $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';
        if (!file_exists($controllerFile)) {
            http_response_code(500);
            echo "Controller file '$controllerName.php' not found.";
            return;
        }

        require_once __DIR__ . '/../controllers/Controller.php';
        require_once $controllerFile;

        if (!class_exists($controllerName)) {
            http_response_code(500);
            echo "Controller class '$controllerName' not found.";
            return;
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $methodName)) {
            http_response_code(500);
            echo "Method '$methodName' not found in controller '$controllerName'.";
            return;
        }

        $controller->$methodName();
    }

    private function stripBasePath(string $path): string
    {
        // Remove the base URL path (e.g., /app/public)
        $base = defined('BASE_URL') ? BASE_URL : '';
        
        // Strip /app/public from the path if present
        if (strpos($path, '/app/public') === 0) {
            $path = substr($path, strlen('/app/public'));
        }
        
        // Also strip the BASE_URL if it's defined and matches
        if ($base && strpos($path, $base) === 0) {
            $path = substr($path, strlen($base));
        }
        
        // Normalize: ensure we have at least a slash, remove trailing slashes except root
        $path = trim($path, '/');
        return $path === '' ? '/' : '/' . $path;
    }

    private function normalizePath(string $path): string
    {
        $path = '/' . trim($path, '/');
        return $path === '/' ? '/' : rtrim($path, '/');
    }
}


