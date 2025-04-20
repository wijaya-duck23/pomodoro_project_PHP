<?php
/**
 * Database Connection Class
 * Handles PDO database connection
 */
class Database
{
    private $host;
    private $username;
    private $password;
    private $database;
    private $connection;

    /**
     * Constructor - loads database configuration
     */
    public function __construct()
    {
        // Load configuration
        $configPath = __DIR__ . '/../config/config.php';
        
        if (!file_exists($configPath)) {
            die("Config file not found at: " . $configPath);
        }
        
        $config = require_once $configPath;
        
        if (!is_array($config)) {
            die("Config file did not return an array. Check your config.php file.");
        }
        
        $this->host = $config['db_host'] ?? 'localhost';
        $this->username = $config['db_user'] ?? 'root';
        $this->password = $config['db_pass'] ?? 'wijaya28@';
        $this->database = $config['db_name'] ?? 'pomodoro_app';
    }

    /**
     * Connect to the database
     * @return PDO connection
     */
    public function connect()
    {
        if ($this->connection === null) {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->database};charset=utf8mb4";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
                
                $this->connection = new PDO($dsn, $this->username, $this->password, $options);
                
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        
        return $this->connection;
    }
} 