<?php
/**
 * Direct access to timer view (bypassing MVC)
 * Use this to test if the issue is with routing
 */

// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set BASE_PATH constant
define('BASE_PATH', realpath(__DIR__ . '/..'));

// Load config manually
$configPath = BASE_PATH . '/config/config.php';
if (file_exists($configPath)) {
    $config = include $configPath;
} else {
    // Fallback config
    $config = [
        'pomodoro_duration' => 25 * 60,
        'short_break_duration' => 5 * 60,
        'long_break_duration' => 15 * 60
    ];
}

// Set timer settings from config
$timerSettings = [
    'pomodoro' => $config['pomodoro_duration'] ?? 25 * 60,
    'short_break' => $config['short_break_duration'] ?? 5 * 60,
    'long_break' => $config['long_break_duration'] ?? 15 * 60
];

// Include the timer view directly
$viewPath = BASE_PATH . '/app/views/timer/index.php';
if (file_exists($viewPath)) {
    require_once $viewPath;
} else {
    echo "<h1>Error</h1>";
    echo "<p>Timer view not found at: $viewPath</p>";
}
?> 