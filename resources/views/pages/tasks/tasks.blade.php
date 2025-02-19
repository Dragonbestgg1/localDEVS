<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl sm:text-lg text-gray-800 dark:text-gray-200 leading-tight">
            Visi Uzdevumi
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-2 sm:p-6">
            <!-- Combined Search & Action Container -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-4">
                <button
                    id="routeButton"
                    class="px-2 py-1 sm:px-4 sm:py-2 text-xs sm:text-sm bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 rounded shadow">
                    Loading...
                </button>
                <input
                    id="taskSearch"
                    type="text"
                    placeholder="Search by task name"
                    class="w-full sm:flex-1 p-1 sm:p-2 text-xs sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-gray-500">
            </div>

            <!-- Task Table -->
            <div class="overflow-x-auto">
                <table class="w-full min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-2 py-1 sm:px-6 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-200 tracking-wider">
                                Task Name
                            </th>
                            <th scope="col" class="px-2 py-1 sm:px-6 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-200 tracking-wider">
                                Code
                            </th>
                        </tr>
                    </thead>
                    <tbody id="taskTableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr>
                            <td colspan="3" class="px-2 py-1 sm:px-6 sm:py-4 text-center text-xs sm:text-sm text-gray-500 dark:text-gray-300">
                                Loading tasks...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        async function updateButton() {
            try {
                const response = await fetch('/get-class');
                const data = await response.json();

                const button = document.getElementById('routeButton');

                if (data.class) {
                    if (data.class === 'teacher') {
                        button.textContent = 'Pievienot Uzdevumu';
                        button.addEventListener('click', () => {
                            window.location.href = '/tasks/addTask';
                        });
                        1
                    } else {
                        button.textContent = 'Mani iesūtījumi';
                        button.addEventListener('click', () => {
                            window.location.href = '/tasks/mysubmissions';
                        });
                    }
                } else {
                    button.textContent = 'Class not found!';
                }
            } catch (error) {
                console.error('Error fetching class:', error);
                button.textContent = 'Error loading class!';
            }
        }

        async function loadTasks() {
            try {
                const response = await fetch('/tasks/all');
                const tasks = await response.json();

                const taskTableBody = document.getElementById('taskTableBody');
                taskTableBody.innerHTML = ''; // Clear the loading text

                if (tasks.length === 0) {
                    taskTableBody.textContent = 'No tasks available.';
                } else {
                    tasks.forEach(function(task) {
                        const row = document.createElement('tr');

                        row.innerHTML = `
                            <td><a href="#" onclick='redirectToTask(${JSON.stringify(task)})'>${task.name}</a></td>
                            <td>${task.code}</td>
                        `;

                        taskTableBody.appendChild(row);
                    });
                }
            } catch (error) {
                console.error('Error loading tasks:', error);
                const taskTableBody = document.getElementById('taskTableBody');
                taskTableBody.textContent = 'Error loading tasks!';
            }
        }

        function redirectToTask(task) {
            // Store the task data in localStorage
            localStorage.setItem('task', JSON.stringify(task));
            // Redirect to the task detail page
            window.location.href = '/task-detail';
        }
        // Search functionality: filters tasks by name (case-insensitive)
        document.getElementById('taskSearch').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const filteredTasks = tasksData.filter(task => task.name.toLowerCase().includes(query));
            displayTasks(filteredTasks);
        });

        updateButton();
        loadTasks();
    </script>
</x-app-layout>