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

// Timer settings
$timerSettings = [
    'pomodoro' => 25 * 60,
    'short_break' => 5 * 60,
    'long_break' => 15 * 60
];

// Include the timer view directly
require_once BASE_PATH . '/app/views/timer/index.php';
?> 