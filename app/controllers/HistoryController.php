<?php
namespace App\Controllers;

use App\Models\Session;

/**
 * History Controller
 * Handles session history operations
 */
class HistoryController
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
     * Display the history page
     * @return void
     */
    public function index()
    {
        // Get all sessions
        $sessions = $this->sessionModel->getAll();
        
        // Include view file
        require_once __DIR__ . '/../views/history/index.php';
    }

    /**
     * Get all sessions as JSON for AJAX requests
     * @return void
     */
    public function list()
    {
        // Get all sessions
        $sessions = $this->sessionModel->getAll();
        
        // Format the data for display
        $formattedSessions = array_map(function($session) {
            return [
                'id' => $session['id'],
                'task_name' => $session['task_name'],
                'session_type' => $session['session_type'],
                'duration' => $this->formatDuration($session['duration']),
                'duration_raw' => $session['duration'],
                'completed_at' => date('Y-m-d H:i:s', strtotime($session['completed_at'])),
                'completed_date' => date('Y-m-d', strtotime($session['completed_at'])),
            ];
        }, $sessions);
        
        // Return JSON response
        $this->sendJsonResponse($formattedSessions);
    }

    /**
     * Filter sessions
     * @return void
     */
    public function filter()
    {
        // Get filter parameters
        $filters = [];
        
        if (!empty($_GET['session_type'])) {
            $filters['session_type'] = filter_var($_GET['session_type'], FILTER_SANITIZE_STRING);
        }
        
        if (!empty($_GET['date'])) {
            $filters['date'] = filter_var($_GET['date'], FILTER_SANITIZE_STRING);
        }
        
        if (!empty($_GET['task_name'])) {
            $filters['task_name'] = filter_var($_GET['task_name'], FILTER_SANITIZE_STRING);
        }
        
        // Get filtered sessions
        $sessions = $this->sessionModel->getFiltered($filters);
        
        // Format the data for display
        $formattedSessions = array_map(function($session) {
            return [
                'id' => $session['id'],
                'task_name' => $session['task_name'],
                'session_type' => $session['session_type'],
                'duration' => $this->formatDuration($session['duration']),
                'duration_raw' => $session['duration'],
                'completed_at' => date('Y-m-d H:i:s', strtotime($session['completed_at'])),
                'completed_date' => date('Y-m-d', strtotime($session['completed_at'])),
            ];
        }, $sessions);
        
        // Return JSON response
        $this->sendJsonResponse($formattedSessions);
    }
    
    /**
     * Format duration in seconds to human-readable format
     * @param int $seconds
     * @return string
     */
    private function formatDuration($seconds)
    {
        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;
        
        return sprintf('%02d:%02d', $minutes, $remainingSeconds);
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