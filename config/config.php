<?php
/**
 * Application Configuration
 */
return [
    // Environment: development, production
    'environment' => 'development',
    
    // Database Configuration
    'db_host' => 'localhost',
    'db_name' => 'pomodoro_app',
    'db_user' => 'root',  // Change in production
    'db_pass' => 'wijaya28@',      // Change in production
    
    // Application Settings
    'app_name' => 'Pomodoro Timer',
    'app_url' => 'http://202.10.44.29/',  // Change for production
    
    // Timer Settings
    'pomodoro_duration' => 25 * 60,     // 25 minutes in seconds
    'short_break_duration' => 5 * 60,   // 5 minutes in seconds
    'long_break_duration' => 15 * 60,   // 15 minutes in seconds
]; 