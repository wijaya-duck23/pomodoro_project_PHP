<?php
namespace App\Controllers;

use App\Models\Session;

/**
 * Timer Controller
 * Handles timer-related operations
 */
class TimerController
{
    /**
     * @var Session
     */
    private $sessionModel;

    /**
     * Constructor - initialize models
     */
    public function __construct()
    {
        $this->sessionModel = new Session();
    }

    /**
     * Display the timer page
     * @return void
     */
    public function index()
    {
        $config = require_once __DIR__ . '/../../config/config.php';
        
        $timerSettings = [
            'pomodoro' => $config['pomodoro_duration'],
            'short_break' => $config['short_break_duration'],
            'long_break' => $config['long_break_duration']
        ];
        
        require_once __DIR__ . '/../views/timer/index.php';
    }

    /**
     * Save a completed timer session
     * @return void
     */
    public function save()
    {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonResponse(['success' => false, 'message' => 'Invalid request method'], 405);
            return;
        }

        // Get JSON data from request body
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);
        
        if (!$data) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Invalid JSON data'], 400);
            return;
        }
        
        // Validate required fields
        if (empty($data['session_type']) || empty($data['duration'])) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Missing required fields'], 400);
            return;
        }
        
        // Sanitize input
        $sessionType = filter_var($data['session_type'], FILTER_SANITIZE_STRING);
        $taskName = !empty($data['task_name']) ? filter_var($data['task_name'], FILTER_SANITIZE_STRING) : 'Unnamed Task';
        $duration = (int) $data['duration'];
        
        // Validate session type
        $validTypes = ['pomodoro', 'short_break', 'long_break'];
        if (!in_array($sessionType, $validTypes)) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Invalid session type'], 400);
            return;
        }
        
        // Save to database
        $result = $this->sessionModel->save($taskName, $sessionType, $duration);
        
        if ($result) {
            $this->sendJsonResponse(['success' => true, 'message' => 'Session saved successfully']);
        } else {
            $this->sendJsonResponse(['success' => false, 'message' => 'Error saving session'], 500);
        }
    }
    
    /**
     * Send JSON response
     * @param array $data
     * @param int $statusCode
     * @return void
     */
    private function sendJsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
} 