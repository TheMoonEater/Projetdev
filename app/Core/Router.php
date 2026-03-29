<?php
declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, string $action): void { $this->add('GET', $path, $action); }
    public function post(string $path, string $action): void { $this->add('POST', $path, $action); }

    private function add(string $method, string $path, string $action): void
    {
        $pattern = preg_replace('#\{[\w]+\}#', '([\w-]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = compact('method', 'path', 'action', 'pattern');
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;

            if (preg_match($route['pattern'], $path, $matches)) {
                array_shift($matches);

                [$controllerName, $methodName] = explode('@', $route['action']);
                $controllerClass = "App\\Controllers\\{$controllerName}";

                if (!class_exists($controllerClass)) {
                    http_response_code(500);
                    echo "Controller introuvable";
                    return;
                }

                $controller = new $controllerClass();

                if (!method_exists($controller, $methodName)) {
                    http_response_code(500);
                    echo "Méthode introuvable";
                    return;
                }

                call_user_func_array([$controller, $methodName], $matches);
                return;
            }
        }

        http_response_code(404);
        echo "404 - Page introuvable";
    }
}
