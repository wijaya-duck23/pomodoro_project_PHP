<?php
/**
 * Router Class
 * Handles URL routing
 */
class Router
{
    private $routes = [
        'GET' => [],
        'POST' => []
    ];

    /**
     * Register a GET route
     * @param string $uri - Route URI
     * @param array $controller - Controller and method as array
     * @return void
     */
    public function get($uri, $controller)
    {
        $this->routes['GET'][$uri] = $controller;
    }

    /**
     * Register a POST route
     * @param string $uri - Route URI
     * @param array $controller - Controller and method as array
     * @return void
     */
    public function post($uri, $controller)
    {
        $this->routes['POST'][$uri] = $controller;
    }

    /**
     * Load routes from routes file
     * @param string $routesFile - Path to routes file
     * @return Router
     */
    public function load($routesFile)
    {
        if (!file_exists($routesFile)) {
            die("Routes file not found at: " . $routesFile);
        }
        
        // Make router available to the routes file
        $router = $this;
        
        require_once $routesFile;
        return $this;
    }

    /**
     * Direct request to the appropriate controller
     * @param string $uri - Request URI
     * @param string $method - Request method
     * @return mixed
     */
    public function direct($uri, $method)
    {
        // Remove query string if present
        if (strpos($uri, '?') !== false) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        
        // Trim slashes
        $uri = trim($uri, '/');
        
        // Default to home if empty
        if (empty($uri)) {
            $uri = 'timer';
        }

        if (array_key_exists($uri, $this->routes[$method])) {
            return $this->callAction(
                ...explode('@', $this->routes[$method][$uri])
            );
        }

        throw new Exception('No route defined for this URI.');
    }

    /**
     * Call the controller action
     * @param string $controller - Controller name
     * @param string $action - Method name
     * @return mixed
     */
    protected function callAction($controller, $action)
    {
        $controller = "App\\Controllers\\{$controller}";
        
        // Check if controller class exists
        if (!class_exists($controller)) {
            throw new Exception("Controller {$controller} not found.");
        }
        
        $controller = new $controller();
        
        if (!method_exists($controller, $action)) {
            throw new Exception(
                "{$controller} does not respond to the {$action} action."
            );
        }
        
        return $controller->$action();
    }
} 