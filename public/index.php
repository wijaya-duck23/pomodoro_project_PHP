<?php
/**
 * Main Application Entry Point
 */

// Turn on error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

if ($baseDir != '/' && $baseDir != '\\') {
    // If the URI starts with the base directory, remove it
    if (strpos($uri, $baseDir) === 0) {
        $uri = substr($uri, strlen($baseDir));
    }
}

// Debug information (in development only)
if (strpos($uri, '/debug') === 0) {
    echo "<h1>Debug Information</h1>";
    echo "<pre>";
    echo "URI: " . $uri . "\n";
    echo "Base Dir: " . $baseDir . "\n";
    echo "Script Name: " . $scriptName . "\n";
    echo "</pre>";
    exit;
}

// Route the request
try {
    // Special case for empty URI - explicitly load and use the timer controller
    if (empty($uri) || $uri == '/' || $uri == '/index.php') {
        // Include the controller file directly
        $controllerPath = BASE_PATH . '/app/controllers/TimerController.php';
        if (!file_exists($controllerPath)) {
            throw new Exception("Controller file not found: " . $controllerPath);
        }
        
        // Load the controller file
        require_once $controllerPath;
        
        // Load the Session model which is used by TimerController
        $sessionModelPath = BASE_PATH . '/app/models/Session.php';
        if (file_exists($sessionModelPath)) {
            require_once $sessionModelPath;
        }
        
        // Create controller instance - use fully qualified class name
        $controller = new \App\Controllers\TimerController();
        $controller->index();
    } else {
        $router->direct($uri, $_SERVER['REQUEST_METHOD']);
    }
} catch (Exception $e) {
    // Output error for debugging
    echo "<h1>Error</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    
    // Handle 404 or other errors
    http_response_code(404);
    require BASE_PATH . '/app/views/errors/404.php';
} 