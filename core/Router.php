<?php

declare(strict_types=1);

namespace Core;

final class Router
{
    private array $routes = [];

    public function add(string $method, string $path, callable|array $handler, array $middlewares = []): void
    {
        $this->routes[] = compact('method', 'path', 'handler', 'middlewares');
    }

    public function dispatch(Request $request): void
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $request->method() || $route['path'] !== $request->path()) {
                continue;
            }
            foreach ($route['middlewares'] as $middleware) {
                (new $middleware())->handle($request);
            }
            if (is_array($route['handler'])) {
                [$class, $method] = $route['handler'];
                (new $class())->{$method}($request);
            } else {
                $route['handler']($request);
            }
            return;
        }
        http_response_code(404);
        echo '404 Not Found';
    }
}
