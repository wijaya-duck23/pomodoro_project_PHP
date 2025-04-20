<?php
// Debug script to test config loading

// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Config Test</h2>";

// Define the base path
define('BASE_PATH', realpath(__DIR__ . '/..'));

// Check if config file exists
$configPath = BASE_PATH . '/config/config.php';
echo "Config path: " . $configPath . "<br>";
echo "File exists: " . (file_exists($configPath) ? "Yes" : "No") . "<br>";

// Try to read file contents directly
echo "<h3>Direct file content:</h3>";
echo "<pre>";
echo htmlspecialchars(file_get_contents($configPath));
echo "</pre>";

// Try to include the file and see if it returns an array
echo "<h3>Testing require_once:</h3>";
try {
    $config = require_once $configPath;
    echo "Config loaded successfully.<br>";
    echo "Is array: " . (is_array($config) ? "Yes" : "No") . "<br>";
    echo "Config type: " . gettype($config) . "<br>";
    
    if (is_array($config)) {
        echo "<h3>Config Contents:</h3>";
        echo "<pre>";
        print_r($config);
        echo "</pre>";
    }
} catch (Exception $e) {
    echo "Error loading config: " . $e->getMessage() . "<br>";
}

// Check file permissions
echo "<h3>File Permissions:</h3>";
echo "Permissions: " . substr(sprintf('%o', fileperms($configPath)), -4) . "<br>";
echo "Owner: " . fileowner($configPath) . "<br>";
echo "Group: " . filegroup($configPath) . "<br>";

// Check if there are any BOM or hidden characters
echo "<h3>Check for BOM:</h3>";
$contents = file_get_contents($configPath);
$bom = bin2hex(substr($contents, 0, 3));
echo "First 3 bytes (hex): " . $bom . "<br>";
echo "Has BOM: " . ($bom === 'efbbbf' ? "Yes" : "No") . "<br>";
?> 