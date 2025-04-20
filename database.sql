-- Create database
CREATE DATABASE IF NOT EXISTS pomodoro_app;

-- Use database
USE pomodoro_app;

-- Create sessions table
CREATE TABLE IF NOT EXISTS sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    task_name VARCHAR(100) NOT NULL,
    session_type ENUM('pomodoro', 'short_break', 'long_break') NOT NULL,
    duration INT NOT NULL COMMENT 'Duration in seconds',
    completed_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add indexes
CREATE INDEX idx_session_type ON sessions(session_type);
CREATE INDEX idx_completed_at ON sessions(completed_at);
CREATE INDEX idx_user_id ON sessions(user_id); 