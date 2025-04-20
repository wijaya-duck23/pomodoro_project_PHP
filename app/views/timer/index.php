<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pomodoro Timer</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Custom styles -->
    <link rel="stylesheet" href="/css/custom.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-center text-red-600">Pomodoro Timer</h1>
            <nav class="mt-4 flex justify-center space-x-4">
                <a href="/timer" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Timer</a>
                <a href="/history" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">History</a>
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

    <!-- JavaScript -->
    <script src="/js/timer.js"></script>
</body>
</html> 