<?php
// Test script to load the configuration
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Attempting to load config file...\n";

$configPath = __DIR__ . '/config/config.php';
echo "Config path: " . $configPath . "\n";
echo "File exists: " . (file_exists($configPath) ? 'Yes' : 'No') . "\n";

if (file_exists($configPath)) {
    echo "File size: " . filesize($configPath) . " bytes\n";
    echo "File permissions: " . substr(sprintf('%o', fileperms($configPath)), -4) . "\n";
    
    echo "File contents (first 100 chars):\n";
    echo substr(file_get_contents($configPath), 0, 100) . "...\n\n";
    
    echo "Attempting to require the file...\n";
    $config = require $configPath;
    
    echo "Type of returned value: " . gettype($config) . "\n";
    
    if (is_array($config)) {
        echo "Config is an array! Contents:\n";
        print_r($config);
    } else {
        echo "Config is NOT an array!\n";
    }
} else {
    echo "Config file does not exist!\n";
} 