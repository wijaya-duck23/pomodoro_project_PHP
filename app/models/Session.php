<?php
namespace App\Models;

/**
 * Session Model
 * Handles database operations for timer sessions
 */
class Session
{
    /**
     * @var \PDO
     */
    private $db;

    /**
     * Constructor - initialize database connection
     */
    public function __construct()
    {
        $database = new \Database();
        $this->db = $database->connect();
    }

    /**
     * Save a completed session
     * @param string $taskName
     * @param string $sessionType
     * @param int $duration
     * @param int|null $userId
     * @return bool
     */
    public function save($taskName, $sessionType, $duration, $userId = null)
    {
        $query = "INSERT INTO sessions (user_id, task_name, session_type, duration, completed_at) 
                  VALUES (:user_id, :task_name, :session_type, :duration, NOW())";
                  
        $statement = $this->db->prepare($query);
        
        return $statement->execute([
            'user_id' => $userId,
            'task_name' => $taskName,
            'session_type' => $sessionType,
            'duration' => $duration,
            'completed_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get all sessions
     * @param int|null $userId
     * @return array
     */
    public function getAll($userId = null)
    {
        $query = "SELECT * FROM sessions";
        $params = [];
        
        if ($userId) {
            $query .= " WHERE user_id = :user_id";
            $params['user_id'] = $userId;
        }
        
        $query .= " ORDER BY completed_at DESC";
        
        $statement = $this->db->prepare($query);
        $statement->execute($params);
        
        return $statement->fetchAll();
    }

    /**
     * Get filtered sessions
     * @param array $filters
     * @param int|null $userId
     * @return array
     */
    public function getFiltered($filters, $userId = null)
    {
        $query = "SELECT * FROM sessions WHERE 1=1";
        $params = [];
        
        if ($userId) {
            $query .= " AND user_id = :user_id";
            $params['user_id'] = $userId;
        }
        
        if (!empty($filters['session_type'])) {
            $query .= " AND session_type = :session_type";
            $params['session_type'] = $filters['session_type'];
        }
        
        if (!empty($filters['date'])) {
            $query .= " AND DATE(completed_at) = :date";
            $params['date'] = $filters['date'];
        }
        
        if (!empty($filters['task_name'])) {
            $query .= " AND task_name LIKE :task_name";
            $params['task_name'] = '%' . $filters['task_name'] . '%';
        }
        
        $query .= " ORDER BY completed_at DESC";
        
        $statement = $this->db->prepare($query);
        $statement->execute($params);
        
        return $statement->fetchAll();
    }
} 