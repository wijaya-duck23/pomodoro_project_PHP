<?php
/**
 * Main Application Entry Point
 */

// Set BASE_PATH constant
define('BASE_PATH', realpath(__DIR__ . '/..'));

// Register Autoloader
spl_autoload_register(function ($class) {
    // Convert namespace to file path
    $class = str_replace('\\', '/', $class);
    
    // Check if it's an application class
    if (strpos($class, 'App/') === 0) {
        $path = BASE_PATH . '/' . strtolower($class) . '.php';
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
    
    // Check if it's a core class
    $corePath = BASE_PATH . '/core/' . $class . '.php';
    if (file_exists($corePath)) {
        require_once $corePath;
    }
});

// Load core classes
require_once BASE_PATH . '/core/App.php';
require_once BASE_PATH . '/core/Router.php';
require_once BASE_PATH . '/core/Database.php';

// Initialize the application
App::init();

// Get router instance
$router = App::getRouter();

// Load routes
$router->load(BASE_PATH . '/routes/web.php');

// Get the request URI and method
$uri = $_SERVER['REQUEST_URI'];

// Extract base path from the URI if needed
$scriptName = $_SERVER['SCRIPT_NAME'];
$baseDir = dirname($scriptName);

if ($baseDir != '/') {
    $uri = substr($uri, strlen($baseDir));
}

// Route the request
try {
    $router->direct($uri, $_SERVER['REQUEST_METHOD']);
} catch (Exception $e) {
    // Handle 404 or other errors
    http_response_code(404);
    require BASE_PATH . '/app/views/errors/404.php';
} 