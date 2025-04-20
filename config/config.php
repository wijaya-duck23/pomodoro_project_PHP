<?php
/**
 * Application Configuration
 */
return array(
    // Environment: development, production
    'environment' => 'development',
    
    // Database Configuration
    'db_host' => 'localhost',
    'db_name' => 'pomodoro_app',
    'db_user' => 'root',
    'db_pass' => 'wijaya28@',
    
    // Application Settings
    'app_name' => 'Pomodoro Timer',
    'app_url' => 'http://202.10.44.29',
    
    // Timer Settings
    'pomodoro_duration' => 1500,     // 25 minutes in seconds
    'short_break_duration' => 300,   // 5 minutes in seconds
    'long_break_duration' => 900     // 15 minutes in seconds
); 