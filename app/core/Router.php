<?php
class Router {
    private $routes = [];

    public function add($route, $controller, $method) {
        $this->routes[$route] = ['controller' => $controller, 'method' => $method];
    }

    public function dispatch($url) {
        $url = $this->removeQueryString($url);
        
        foreach ($this->routes as $route => $params) {
            // First try exact match
            if ($url === $route) {
                $controller = $params['controller'];
                $method = $params['method'];
                
                require_once '../app/controllers/' . $controller . '.php';
                $controllerInstance = new $controller();
                $controllerInstance->$method();
                return;
            }
            
            // Then try pattern matching for routes with parameters
            if (preg_match('#^' . preg_quote($route, '#') . '(/.*)?$#', $url, $matches)) {
                $controller = $params['controller'];
                $method = $params['method'];
                
                require_once '../app/controllers/' . $controller . '.php';
                $controllerInstance = new $controller();
                
                // Extract parameter if exists
                if (isset($matches[1])) {
                    $param = ltrim($matches[1], '/');
                    $controllerInstance->$method($param);
                } else {
                    $controllerInstance->$method();
                }
                return;
            }
        }
        
        // 404 - Page not found
        http_response_code(404);
        echo "<!DOCTYPE html>
<html>
<head>
    <title>Page Not Found</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body class='bg-light'>
    <div class='container mt-5'>
        <div class='text-center'>
            <h1 class='display-1 text-danger'>404</h1>
            <h2>Page Not Found</h2>
            <p class='lead'>The page you are looking for could not be found.</p>
            <a href='/' class='btn btn-primary'>Go Home</a>
        </div>
    </div>
</body>
</html>";
    }

    private function removeQueryString($url) {
        if ($url != '') {
            $parts = explode('?', $url, 2);
            return $parts[0];
        }
        return $url;
    }
    
    public function getRoutes() {
        return $this->routes;
    }
}
?>