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
        <!-- Use border-separate and border-spacing-y-2 for vertical spacing -->
        <table class="w-full min-w-full border-separate border-spacing-y-2">
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
          <tbody id="taskTableBody" class="bg-white dark:bg-gray-800">
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
    // Global array to store tasks data
    let tasksData = [];

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
        const button = document.getElementById('routeButton');
        button.textContent = 'Error loading class!';
      }
    }

    async function loadTasks() {
      try {
        const response = await fetch('/tasks/all');
        tasksData = await response.json();
        const taskTableBody = document.getElementById('taskTableBody');

        // Clear the placeholder text
        taskTableBody.innerHTML = '';

        if (tasksData.length === 0) {
          taskTableBody.textContent = 'No tasks available.';
        } else {
          tasksData.forEach(function(task) {
            const row = document.createElement('tr');
            // Add hover and transition classes
            row.classList.add(
              'hover:bg-gray-100',
              'dark:hover:bg-gray-700',
              'transition-colors',
              'duration-200'
            );
            row.innerHTML = `
              <td class="px-2 py-1 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                <a href="#" onclick='redirectToTask(${JSON.stringify(task)})'
                   class="text-gray-900 dark:text-gray-200 hover:underline font-semibold">
                  ${task.name}
                </a>
              </td>
              <td class="px-2 py-1 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                <!-- Use a neutral or transparent background in dark mode -->
                <span class="font-mono text-xs sm:text-sm bg-gray-50 dark:bg-transparent p-1 rounded text-gray-900 dark:text-gray-100">
                  ${task.code}
                </span>
              </td>
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

    function displayTasks(tasks) {
      const taskTableBody = document.getElementById('taskTableBody');
      taskTableBody.innerHTML = '';

      if (tasks.length === 0) {
        taskTableBody.textContent = 'No tasks available.';
      } else {
        tasks.forEach(function(task) {
          const row = document.createElement('tr');
          row.classList.add(
            'hover:bg-gray-100',
            'dark:hover:bg-gray-700',
            'transition-colors',
            'duration-200'
          );
          row.innerHTML = `
            <td class="px-2 py-1 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
              <a href="#" onclick='redirectToTask(${JSON.stringify(task)})'
                 class="text-gray-900 dark:text-gray-200 hover:underline font-semibold">
                ${task.name}
              </a>
            </td>
            <td class="px-2 py-1 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
              <span class="font-mono text-xs sm:text-sm bg-gray-50 dark:bg-transparent p-1 rounded text-gray-900 dark:text-gray-100">
                ${task.code}
              </span>
            </td>
          `;
          taskTableBody.appendChild(row);
        });
      }
    }

    function redirectToTask(task) {
      // Store the task data in localStorage and redirect
      localStorage.setItem('task', JSON.stringify(task));
      window.location.href = '/task-detail';
    }

    // Search functionality: filter tasks by name (case-insensitive)
    document.getElementById('taskSearch').addEventListener('input', function() {
      const query = this.value.toLowerCase();
      const filteredTasks = tasksData.filter(task => task.name.toLowerCase().includes(query));
      displayTasks(filteredTasks);
    });
    
    updateButton();
    loadTasks();
  </script>
</x-app-layout>