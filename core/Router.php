<?php

declare(strict_types=1);

namespace Core;

final class Router
{
    private array $routes = [];
    private array $groupMiddleware = [];
    private string $groupPrefix = '';

    public function get(string $path, string $action, array $middleware = []): self
    {
        return $this->addRoute('GET', $path, $action, $middleware);
    }

    public function post(string $path, string $action, array $middleware = []): self
    {
        return $this->addRoute('POST', $path, $action, $middleware);
    }

    public function group(string $prefix, array $middleware, callable $callback): void
    {
        $previousPrefix = $this->groupPrefix;
        $previousMiddleware = $this->groupMiddleware;

        $this->groupPrefix .= '/' . trim($prefix, '/');
        $this->groupMiddleware = array_merge($this->groupMiddleware, $middleware);

        $callback($this);

        $this->groupPrefix = $previousPrefix;
        $this->groupMiddleware = $previousMiddleware;
    }

    private function addRoute(string $method, string $path, string $action, array $middleware): self
    {
        $fullPath = $this->groupPrefix . '/' . trim($path, '/');
        $fullPath = rtrim($fullPath, '/') ?: '/';

        $this->routes[] = [
            'method'     => $method,
            'path'       => $fullPath,
            'action'     => $action,
            'middleware'  => array_merge($this->groupMiddleware, $middleware),
            'pattern'    => $this->buildPattern($fullPath),
        ];

        return $this;
    }

    private function buildPattern(string $path): string
    {
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[a-zA-Z0-9_-]+)', $path);
        return '#^' . $pattern . '$#';
    }

    public function resolve(Request $request): ?array
    {
        $uri = '/' . trim($request->uri(), '/');
        if ($uri !== '/') {
            $uri = rtrim($uri, '/');
        }
        $method = $request->method();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, fn($key) => !is_int($key), ARRAY_FILTER_USE_KEY);
                return [
                    'action'     => $route['action'],
                    'params'     => $params,
                    'middleware'  => $route['middleware'],
                ];
            }
        }

        return null;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
