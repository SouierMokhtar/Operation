<?php

namespace App\Core;

class App
{
    private array $routes = [];

    public function addRoute(string $method, string $path, string $controller, string $action): void
    {
        $this->routes[] = [
            'method'     => strtoupper($method),
            'path'       => $path,
            'controller' => $controller,
            'action'     => $action,
        ];
    }

    public function get(string $path, string $controller, string $action): void
    {
        $this->addRoute('GET', $path, $controller, $action);
    }

    public function post(string $path, string $controller, string $action): void
    {
        $this->addRoute('POST', $path, $controller, $action);
    }

    public function run(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        $path     = substr($requestUri, strlen($basePath)) ?: '/';
        $path     = '/' . trim($path, '/');

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $route['path'] === $path) {
                $controllerClass = 'App\\Controllers\\' . $route['controller'];

                if (!class_exists($controllerClass)) {
                    die("Contrôleur introuvable : {$route['controller']}");
                }

                $controller = new $controllerClass();
                $action     = $route['action'];

                if (!method_exists($controller, $action)) {
                    die("Action introuvable : {$route['controller']}::{$action}");
                }

                $controller->$action();
                return;
            }
        }

        http_response_code(404);
        echo '<h1>404 - Page non trouvée</h1>';
        echo '<p><a href="' . BASE_URL . '/">Retour au menu principal</a></p>';
    }
}
