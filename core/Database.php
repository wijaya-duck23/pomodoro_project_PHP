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
        $config = require_once __DIR__ . '/../config/config.php';
        
        $this->host = $config['db_host'];
        $this->username = $config['db_user'];
        $this->password = $config['db_pass'];
        $this->database = $config['db_name'];
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