<?php
// Simple standalone timer page

// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pomodoro Timer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles for Pomodoro Timer App */
        button.active {
            font-weight: bold;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .timer-running {
            animation: pulse 2s infinite;
        }

        .session-type-pomodoro { background-color: #dc2626; }
        .session-type-short-break { background-color: #16a34a; }
        .session-type-long-break { background-color: #2563eb; }

        input:focus, select:focus, button:focus {
            outline: 2px solid #dc2626;
            outline-offset: 2px;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-center text-red-600">Pomodoro Timer</h1>
            <nav class="mt-4 flex justify-center space-x-4">
                <a href="simple_timer.php" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Timer</a>
                <a href="#" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">History</a>
            </nav>
        </header>

        <main class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
            <div class="mb-6">
                <div class="flex justify-center space-x-2 mb-4">
                    <button id="pomodoro-btn" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 active">Pomodoro</button>
                    <button id="short-break-btn" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Short Break</button>
                    <button id="long-break-btn" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Long Break</button>
                </div>

                <div class="mt-6">
                    <input type="text" id="task-name" placeholder="What are you working on?" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>

                <div class="flex justify-center mt-8">
                    <div id="timer" class="text-6xl font-bold text-center">25:00</div>
                </div>

                <div class="flex justify-center mt-8 space-x-4">
                    <button id="start-btn" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Start</button>
                    <button id="pause-btn" class="px-6 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 hidden">Pause</button>
                    <button id="reset-btn" class="px-6 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Reset</button>
                </div>
            </div>

            <div id="session-info" class="mt-6 pt-4 border-t border-gray-200 hidden">
                <h2 class="text-lg font-semibold mb-2">Current Session</h2>
                <p><span class="font-medium">Session Type:</span> <span id="current-session-type">Pomodoro</span></p>
                <p><span class="font-medium">Task:</span> <span id="current-task-name">-</span></p>
                <p><span class="font-medium">Elapsed Time:</span> <span id="elapsed-time">00:00</span></p>
            </div>
        </main>
    </div>

    <!-- Notification sound for timer completion -->
    <audio id="notification-sound" src="https://assets.mixkit.co/sfx/preview/mixkit-alarm-digital-clock-beep-989.mp3" preload="auto"></audio>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Timer elements
        const timerElement = document.getElementById('timer');
        const startButton = document.getElementById('start-btn');
        const pauseButton = document.getElementById('pause-btn');
        const resetButton = document.getElementById('reset-btn');
        
        // Session type buttons
        const pomodoroButton = document.getElementById('pomodoro-btn');
        const shortBreakButton = document.getElementById('short-break-btn');
        const longBreakButton = document.getElementById('long-break-btn');
        
        // Task name input
        const taskNameInput = document.getElementById('task-name');
        
        // Session info elements
        const sessionInfoElement = document.getElementById('session-info');
        const currentSessionTypeElement = document.getElementById('current-session-type');
        const currentTaskNameElement = document.getElementById('current-task-name');
        const elapsedTimeElement = document.getElementById('elapsed-time');
        
        // Audio notification
        const notificationSound = document.getElementById('notification-sound');
        
        // Timer variables
        let timerInterval;
        let timerRunning = false;
        let secondsRemaining = 25 * 60; // Default to pomodoro duration
        let originalSeconds = secondsRemaining;
        let elapsedSeconds = 0;
        let currentSessionType = 'pomodoro';
        
        // Duration settings (in seconds)
        const durations = {
            pomodoro: 25 * 60,     // 25 minutes
            short_break: 5 * 60,   // 5 minutes
            long_break: 15 * 60    // 15 minutes
        };
        
        // Initialize timer display
        updateTimerDisplay();
        
        // Start button event listener
        startButton.addEventListener('click', startTimer);
        
        // Pause button event listener
        pauseButton.addEventListener('click', pauseTimer);
        
        // Reset button event listener
        resetButton.addEventListener('click', resetTimer);
        
        // Session type button event listeners
        pomodoroButton.addEventListener('click', () => setSessionType('pomodoro'));
        shortBreakButton.addEventListener('click', () => setSessionType('short_break'));
        longBreakButton.addEventListener('click', () => setSessionType('long_break'));
        
        function startTimer() {
            if (timerRunning) return;
            
            // Show session info
            sessionInfoElement.classList.remove('hidden');
            
            // Update session info
            currentSessionTypeElement.textContent = formatSessionType(currentSessionType);
            currentTaskNameElement.textContent = taskNameInput.value || 'Unnamed Task';
            
            // Show pause button, hide start button
            startButton.classList.add('hidden');
            pauseButton.classList.remove('hidden');
            
            // Add pulsing animation to timer
            timerElement.classList.add('timer-running');
            
            timerRunning = true;
            
            // Start interval
            timerInterval = setInterval(() => {
                if (secondsRemaining > 0) {
                    secondsRemaining--;
                    elapsedSeconds++;
                    updateTimerDisplay();
                    updateElapsedTime();
                } else {
                    completeTimer();
                }
            }, 1000);
        }
        
        function pauseTimer() {
            if (!timerRunning) return;
            
            clearInterval(timerInterval);
            timerRunning = false;
            
            // Show start button, hide pause button
            startButton.classList.remove('hidden');
            pauseButton.classList.add('hidden');
            
            // Remove pulsing animation
            timerElement.classList.remove('timer-running');
        }
        
        function resetTimer() {
            pauseTimer();
            secondsRemaining = originalSeconds;
            elapsedSeconds = 0;
            updateTimerDisplay();
            updateElapsedTime();
            
            // Hide session info
            sessionInfoElement.classList.add('hidden');
        }
        
        function completeTimer() {
            pauseTimer();
            
            // Play notification sound
            notificationSound.play();
            
            // Show alert
            alert(`${formatSessionType(currentSessionType)} completed!`);
        }
        
        function setSessionType(type) {
            // Reset before changing type
            resetTimer();
            
            currentSessionType = type;
            secondsRemaining = durations[type];
            originalSeconds = secondsRemaining;
            
            // Update timer display
            updateTimerDisplay();
            
            // Update button styles
            pomodoroButton.classList.remove('bg-red-600', 'text-white');
            shortBreakButton.classList.remove('bg-red-600', 'text-white');
            longBreakButton.classList.remove('bg-red-600', 'text-white');
            
            pomodoroButton.classList.add('bg-gray-300', 'text-gray-700');
            shortBreakButton.classList.add('bg-gray-300', 'text-gray-700');
            longBreakButton.classList.add('bg-gray-300', 'text-gray-700');
            
            // Highlight the active button
            switch (type) {
                case 'pomodoro':
                    pomodoroButton.classList.remove('bg-gray-300', 'text-gray-700');
                    pomodoroButton.classList.add('bg-red-600', 'text-white');
                    break;
                case 'short_break':
                    shortBreakButton.classList.remove('bg-gray-300', 'text-gray-700');
                    shortBreakButton.classList.add('bg-red-600', 'text-white');
                    break;
                case 'long_break':
                    longBreakButton.classList.remove('bg-gray-300', 'text-gray-700');
                    longBreakButton.classList.add('bg-red-600', 'text-white');
                    break;
            }
        }
        
        function updateTimerDisplay() {
            const minutes = Math.floor(secondsRemaining / 60);
            const seconds = secondsRemaining % 60;
            
            timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }
        
        function updateElapsedTime() {
            const minutes = Math.floor(elapsedSeconds / 60);
            const seconds = elapsedSeconds % 60;
            
            elapsedTimeElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }
        
        function formatSessionType(type) {
            switch (type) {
                case 'pomodoro':
                    return 'Pomodoro';
                case 'short_break':
                    return 'Short Break';
                case 'long_break':
                    return 'Long Break';
                default:
                    return type;
            }
        }
    });
    </script>
</body>
</html> 