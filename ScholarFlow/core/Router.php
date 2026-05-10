<?php
// core/Router.php — URL Routing Engine

class Router
{
    private array $routes = [];

    public function get(string $path, string $action): void
    {
        $this->addRoute('GET', $path, $action);
    }

    public function post(string $path, string $action): void
    {
        $this->addRoute('POST', $path, $action);
    }

    private function addRoute(string $method, string $path, string $action): void
    {
        // Convert :param style to regex
        $pattern = preg_replace('/\/:([a-zA-Z0-9_]+)/', '/(?P<$1>[^/]+)', $path);
        $pattern = '@^' . $pattern . '$@';
        $this->routes[] = compact('method', 'pattern', 'action', 'path');
    }

    public function dispatch(string $uri, string $method): void
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;

            if (preg_match($route['pattern'], $uri, $matches)) {
                // Extract named params
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->call($route['action'], $params);
                return;
            }
        }

        // 404
        http_response_code(404);
        $this->call('ErrorController@notFound', []);
    }

    private function call(string $action, array $params): void
    {
        [$controllerName, $methodName] = explode('@', $action);
        $controllerFile = ROOT . '/app/Controllers/' . $controllerName . '.php';

        if (!file_exists($controllerFile)) {
            http_response_code(500);
            die("Controller <b>{$controllerName}</b> not found.");
        }

        require_once $controllerFile;
        $controller = new $controllerName();

        if (!method_exists($controller, $methodName)) {
            http_response_code(500);
            die("Method <b>{$methodName}</b> not found in <b>{$controllerName}</b>.");
        }

        call_user_func_array([$controller, $methodName], $params);
    }
}
