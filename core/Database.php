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
        try {
            // Set default values
            $this->host = 'localhost';
            $this->username = 'root';
            $this->password = '';
            $this->database = 'pomodoro_app';
            
            // Try to load configuration
            $configPath = __DIR__ . '/../config/config.php';
            
            if (file_exists($configPath)) {
                $config = @include $configPath;
                
                // Check if config is an array and has the required keys
                if (is_array($config)) {
                    $this->host = isset($config['db_host']) ? $config['db_host'] : $this->host;
                    $this->username = isset($config['db_user']) ? $config['db_user'] : $this->username;
                    $this->password = isset($config['db_pass']) ? $config['db_pass'] : $this->password;
                    $this->database = isset($config['db_name']) ? $config['db_name'] : $this->database;
                }
            }
        } catch (Exception $e) {
            error_log("Error loading database configuration: " . $e->getMessage());
            // Continue with default values
        }
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