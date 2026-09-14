<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, $handler, array $middleware = []): void
    {
        $this->add('GET', $path, $handler, $middleware);
    }

    public function post(string $path, $handler, array $middleware = []): void
    {
        $this->add('POST', $path, $handler, $middleware);
    }

    private function add(string $method, string $path, $handler, array $middleware): void
    {
        $this->routes[] = [
            'method'     => $method,
            'path'       => trim($path, '/') === '' ? '/' : '/' . trim($path, '/'),
            'handler'    => $handler,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(string $method, string $uri): bool
    {
        $uri           = '/' . trim($uri, '/');
        $methodAllowed = false;

        foreach ($this->routes as $route) {
            if (!preg_match($this->toRegex($route['path']), $uri, $matches)) {
                continue;
            }
            if ($route['method'] !== $method) {
                $methodAllowed = true;
                continue;
            }

            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            $this->call($route['handler'], $params, $route['middleware']);
            return true;
        }

        if ($methodAllowed) {
            http_response_code(405);
        }
        return false;
    }

    private function toRegex(string $path): string
    {
        $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#u';
    }

    private function call($handler, array $params, array $middleware): void
    {
        $map = $GLOBALS['APP_MIDDLEWARE'] ?? [];

        foreach ($middleware as $spec) {
            $param = null;
            $name  = $spec;
            if (strpos($spec, ':') !== false) {
                [$name, $param] = explode(':', $spec, 2);
            }
            if (isset($map[$name]) && class_exists($map[$name])) {
                (new $map[$name]())->handle($param);
            }
        }

        if (is_array($handler)) {
            [$class, $method] = $handler;
        } else {
            [$class, $method] = explode('@', $handler);
        }

        if (strpos($class, '\\') === false) {
            $class = 'App\\Controllers\\' . $class;
        }

        $controller = new $class();
        $controller->{$method}(...array_values($params));
    }
}