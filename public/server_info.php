<?php
// Server Info Script

// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Server Information</h1>";

// Check if mod_rewrite is enabled
echo "<h2>Apache Modules</h2>";
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    echo "mod_rewrite enabled: " . (in_array('mod_rewrite', $modules) ? "Yes" : "No") . "<br>";
    echo "<h3>All Apache Modules</h3>";
    echo "<pre>";
    print_r($modules);
    echo "</pre>";
} else {
    echo "Cannot determine Apache modules (apache_get_modules function not available)<br>";
}

// Server software
echo "<h2>Server Software</h2>";
echo $_SERVER['SERVER_SOFTWARE'] . "<br>";

// Document root
echo "<h2>Document Root</h2>";
echo $_SERVER['DOCUMENT_ROOT'] . "<br>";

// Server name
echo "<h2>Server Name</h2>";
echo $_SERVER['SERVER_NAME'] . "<br>";

// Script filename
echo "<h2>Script Filename</h2>";
echo $_SERVER['SCRIPT_FILENAME'] . "<br>";

// PHP version
echo "<h2>PHP Version</h2>";
echo phpversion() . "<br>";

// Directory permissions
echo "<h2>Directory Permissions</h2>";
$publicDir = __DIR__;
$parentDir = dirname($publicDir);
echo "Public directory: " . $publicDir . " - Permissions: " . substr(sprintf('%o', fileperms($publicDir)), -4) . "<br>";
echo "Parent directory: " . $parentDir . " - Permissions: " . substr(sprintf('%o', fileperms($parentDir)), -4) . "<br>";

// Check .htaccess file
echo "<h2>.htaccess File</h2>";
$htaccessPath = __DIR__ . '/.htaccess';
echo "File exists: " . (file_exists($htaccessPath) ? "Yes" : "No") . "<br>";
if (file_exists($htaccessPath)) {
    echo "File permissions: " . substr(sprintf('%o', fileperms($htaccessPath)), -4) . "<br>";
    echo "File contents:<br><pre>";
    echo htmlspecialchars(file_get_contents($htaccessPath));
    echo "</pre>";
}
?> 