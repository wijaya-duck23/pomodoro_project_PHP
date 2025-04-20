<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session History - Pomodoro Timer</title>
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
                <a href="/timer" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Timer</a>
                <a href="/history" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">History</a>
            </nav>
        </header>

        <main class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">Session History</h2>
            
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Filter Sessions</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="filter-type" class="block text-sm font-medium text-gray-700 mb-1">Session Type</label>
                        <select id="filter-type" class="w-full px-3 py-2 border rounded-md">
                            <option value="">All Types</option>
                            <option value="pomodoro">Pomodoro</option>
                            <option value="short_break">Short Break</option>
                            <option value="long_break">Long Break</option>
                        </select>
                    </div>
                    <div>
                        <label for="filter-date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" id="filter-date" class="w-full px-3 py-2 border rounded-md">
                    </div>
                    <div>
                        <label for="filter-task" class="block text-sm font-medium text-gray-700 mb-1">Task Name</label>
                        <input type="text" id="filter-task" placeholder="Search by task..." class="w-full px-3 py-2 border rounded-md">
                    </div>
                </div>
                <div class="mt-4">
                    <button id="apply-filter" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Apply Filter</button>
                    <button id="clear-filter" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 ml-2">Clear Filter</button>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="py-2 px-4 text-left">ID</th>
                            <th class="py-2 px-4 text-left">Task Name</th>
                            <th class="py-2 px-4 text-left">Session Type</th>
                            <th class="py-2 px-4 text-left">Duration</th>
                            <th class="py-2 px-4 text-left">Completed At</th>
                        </tr>
                    </thead>
                    <tbody id="sessions-table-body">
                        <?php if (empty($sessions)) : ?>
                            <tr>
                                <td colspan="5" class="py-4 px-4 text-center text-gray-500">No sessions found</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($sessions as $session) : ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-2 px-4"><?= $session['id'] ?></td>
                                    <td class="py-2 px-4"><?= htmlspecialchars($session['task_name']) ?></td>
                                    <td class="py-2 px-4">
                                        <?php 
                                        $typeClass = 'text-white rounded px-2 py-1 text-sm';
                                        switch($session['session_type']) {
                                            case 'pomodoro':
                                                $typeClass .= ' bg-red-600';
                                                break;
                                            case 'short_break':
                                                $typeClass .= ' bg-green-600';
                                                break;
                                            case 'long_break':
                                                $typeClass .= ' bg-blue-600';
                                                break;
                                        }
                                        ?>
                                        <span class="<?= $typeClass ?>"><?= ucfirst(str_replace('_', ' ', $session['session_type'])) ?></span>
                                    </td>
                                    <td class="py-2 px-4">
                                        <?php 
                                        $minutes = floor($session['duration'] / 60);
                                        $seconds = $session['duration'] % 60;
                                        echo sprintf('%02d:%02d', $minutes, $seconds);
                                        ?>
                                    </td>
                                    <td class="py-2 px-4"><?= date('Y-m-d H:i:s', strtotime($session['completed_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- JavaScript for filtering -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const applyFilterButton = document.getElementById('apply-filter');
            const clearFilterButton = document.getElementById('clear-filter');
            const filterType = document.getElementById('filter-type');
            const filterDate = document.getElementById('filter-date');
            const filterTask = document.getElementById('filter-task');
            
            // Apply filter
            applyFilterButton.addEventListener('click', function() {
                let filterParams = new URLSearchParams();
                
                if (filterType.value) {
                    filterParams.append('session_type', filterType.value);
                }
                
                if (filterDate.value) {
                    filterParams.append('date', filterDate.value);
                }
                
                if (filterTask.value) {
                    filterParams.append('task_name', filterTask.value);
                }
                
                // If we have filter parameters, make AJAX request
                if (filterParams.toString()) {
                    fetch('/history/filter?' + filterParams.toString())
                        .then(response => response.json())
                        .then(data => updateSessionsTable(data))
                        .catch(error => console.error('Error:', error));
                } else {
                    // If no filters, get all sessions
                    fetch('/history/list')
                        .then(response => response.json())
                        .then(data => updateSessionsTable(data))
                        .catch(error => console.error('Error:', error));
                }
            });
            
            // Clear filter
            clearFilterButton.addEventListener('click', function() {
                filterType.value = '';
                filterDate.value = '';
                filterTask.value = '';
                
                // Get all sessions
                fetch('/history/list')
                    .then(response => response.json())
                    .then(data => updateSessionsTable(data))
                    .catch(error => console.error('Error:', error));
            });
            
            // Function to update sessions table
            function updateSessionsTable(sessions) {
                const tableBody = document.getElementById('sessions-table-body');
                tableBody.innerHTML = '';
                
                if (sessions.length === 0) {
                    const row = document.createElement('tr');
                    row.innerHTML = `<td colspan="5" class="py-4 px-4 text-center text-gray-500">No sessions found</td>`;
                    tableBody.appendChild(row);
                    return;
                }
                
                sessions.forEach(session => {
                    const row = document.createElement('tr');
                    row.className = 'border-b hover:bg-gray-50';
                    
                    let typeClass = 'text-white rounded px-2 py-1 text-sm';
                    switch(session.session_type) {
                        case 'pomodoro':
                            typeClass += ' bg-red-600';
                            break;
                        case 'short_break':
                            typeClass += ' bg-green-600';
                            break;
                        case 'long_break':
                            typeClass += ' bg-blue-600';
                            break;
                    }
                    
                    const sessionTypeName = session.session_type.replace('_', ' ');
                    const formattedType = sessionTypeName.charAt(0).toUpperCase() + sessionTypeName.slice(1);
                    
                    row.innerHTML = `
                        <td class="py-2 px-4">${session.id}</td>
                        <td class="py-2 px-4">${session.task_name}</td>
                        <td class="py-2 px-4"><span class="${typeClass}">${formattedType}</span></td>
                        <td class="py-2 px-4">${session.duration}</td>
                        <td class="py-2 px-4">${session.completed_at}</td>
                    `;
                    
                    tableBody.appendChild(row);
                });
            }
        });
    </script>
</body>
</html> 