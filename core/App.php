<?php
/**
 * App Class
 * Main application bootstrapper
 */
class App
{
    /**
     * @var Router
     */
    protected static $router;
    
    /**
     * @var Database
     */
    protected static $database;
    
    /**
     * Initialize the application
     * @return void
     */
    public static function init()
    {
        // Set up error handling
        self::setErrorHandling();
        
        // Initialize router
        self::$router = new Router();
        
        // Initialize database
        self::$database = new Database();
    }
    
    /**
     * Get router instance
     * @return Router
     */
    public static function getRouter()
    {
        return self::$router;
    }
    
    /**
     * Get database instance
     * @return Database
     */
    public static function getDatabase()
    {
        return self::$database;
    }
    
    /**
     * Set up error handling
     * @return void
     */
    protected static function setErrorHandling()
    {
        // Display errors in development environment only
        $config = require_once __DIR__ . '/../config/config.php';
        if ($config['environment'] === 'development') {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 0);
            ini_set('display_startup_errors', 0);
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        }
    }
} 