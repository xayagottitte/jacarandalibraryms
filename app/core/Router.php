<?php
class Router {
    private $routes = [];

    public function add($route, $controller, $method) {
        $this->routes[$route] = ['controller' => $controller, 'method' => $method];
    }

    public function dispatch($url) {
        $url = $this->removeQueryString($url);
        
        foreach ($this->routes as $route => $params) {
            if ($url === $route) {
                $controller = $params['controller'];
                $method = $params['method'];
                
                require_once '../app/controllers/' . $controller . '.php';
                $controllerInstance = new $controller();
                $controllerInstance->$method();
                return;
            }
        }
        
        // 404 - Page not found
        http_response_code(404);
        echo "Page not found";
    }

    private function removeQueryString($url) {
        if ($url != '') {
            $parts = explode('?', $url, 2);
            return $parts[0];
        }
        return $url;
    }
}
?>