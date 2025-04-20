/**
 * Pomodoro Timer JavaScript
 */
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
    
    /**
     * Start the timer
     */
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
    
    /**
     * Pause the timer
     */
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
    
    /**
     * Reset the timer
     */
    function resetTimer() {
        pauseTimer();
        secondsRemaining = originalSeconds;
        elapsedSeconds = 0;
        updateTimerDisplay();
        updateElapsedTime();
        
        // Hide session info
        sessionInfoElement.classList.add('hidden');
    }
    
    /**
     * Complete timer - play sound and save session
     */
    function completeTimer() {
        pauseTimer();
        
        // Play notification sound
        notificationSound.play();
        
        // Save the session to the database
        saveSession();
        
        // Show alert
        alert(`${formatSessionType(currentSessionType)} completed!`);
    }
    
    /**
     * Save session to the database
     */
    function saveSession() {
        const sessionData = {
            task_name: taskNameInput.value || 'Unnamed Task',
            session_type: currentSessionType,
            duration: originalSeconds - secondsRemaining
        };
        
        fetch('/timer/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(sessionData)
        })
        .then(response => response.json())
        .then(data => {
            console.log('Session saved:', data);
        })
        .catch(error => {
            console.error('Error saving session:', error);
        });
    }
    
    /**
     * Set the session type
     * @param {string} type - Session type (pomodoro, short_break, long_break)
     */
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
    
    /**
     * Update the timer display
     */
    function updateTimerDisplay() {
        const minutes = Math.floor(secondsRemaining / 60);
        const seconds = secondsRemaining % 60;
        
        timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
    
    /**
     * Update the elapsed time display
     */
    function updateElapsedTime() {
        const minutes = Math.floor(elapsedSeconds / 60);
        const seconds = elapsedSeconds % 60;
        
        elapsedTimeElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
    
    /**
     * Format session type for display
     * @param {string} type - Session type
     * @return {string} Formatted session type
     */
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