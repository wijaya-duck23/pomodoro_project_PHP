<?php
/**
 * Class Loading Test
 */

// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set BASE_PATH constant
define('BASE_PATH', realpath(__DIR__ . '/..'));

echo "<h1>Class Loading Test</h1>";

// Check Controller file existence
$timerControllerPath = BASE_PATH . '/app/controllers/TimerController.php';
echo "<h2>Controller File Check</h2>";
echo "Controller path: " . $timerControllerPath . "<br>";
echo "File exists: " . (file_exists($timerControllerPath) ? "Yes" : "No") . "<br>";

// Show Controller file content if it exists
if (file_exists($timerControllerPath)) {
    echo "<h3>Controller Content:</h3>";
    echo "<pre>";
    echo htmlspecialchars(file_get_contents($timerControllerPath));
    echo "</pre>";
    
    // Try to manually include the file
    echo "<h3>Trying to include controller:</h3>";
    try {
        require_once $timerControllerPath;
        echo "Controller file included successfully.<br>";
        
        // Check for Session model dependency
        $sessionModelPath = BASE_PATH . '/app/models/Session.php';
        if (file_exists($sessionModelPath)) {
            require_once $sessionModelPath;
            echo "Session model included successfully.<br>";
        } else {
            echo "Session model file not found!<br>";
        }
        
        // Try to create controller instance
        echo "<h3>Trying to instantiate controller:</h3>";
        $controllerClass = "App\\Controllers\\TimerController";
        echo "Controller class name: " . $controllerClass . "<br>";
        
        if (class_exists($controllerClass)) {
            echo "Class exists! Creating instance...<br>";
            $controller = new $controllerClass();
            echo "Controller instance created successfully.";
        } else {
            echo "Class doesn't exist after including the file!<br>";
            echo "Defined classes: <pre>";
            print_r(get_declared_classes());
            echo "</pre>";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
    }
} else {
    echo "Controller file not found!<br>";
}

// Check file system directory structure
echo "<h2>Directory Structure</h2>";
echo "<pre>";
function list_dir($dir, $indent = 0) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file == '.' || $file == '..') continue;
        $path = $dir . '/' . $file;
        echo str_repeat('  ', $indent) . ($file) . (is_dir($path) ? '/' : '') . "\n";
        if (is_dir($path)) {
            list_dir($path, $indent + 1);
        }
    }
}
list_dir(BASE_PATH);
echo "</pre>";
?> 