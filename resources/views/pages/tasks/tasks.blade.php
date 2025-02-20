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

      <!-- Pagination Controls -->
      <div id="task-pagination-controls" class="mt-4 flex justify-center items-center space-x-4"></div>
    </div>
  </div>

  <script>
    // Global variables for tasks pagination
    let tasksList = [];
    let currentPage = 1;
    const itemsPerPage = 10;

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

    // Render tasks for the current page (with pagination)
    function renderTasks(tasks) {
      const taskTableBody = document.getElementById('taskTableBody');
      taskTableBody.innerHTML = '';

      const startIndex = (currentPage - 1) * itemsPerPage;
      const tasksPage = tasks.slice(startIndex, startIndex + itemsPerPage);

      if (tasksPage.length === 0) {
        taskTableBody.textContent = 'No tasks available.';
      } else {
        tasksPage.forEach(function(task) {
          const row = document.createElement('tr');
          // Make the entire row clickable with a pointer cursor
          row.classList.add(
            'cursor-pointer',
            'hover:bg-gray-100',
            'dark:hover:bg-gray-700',
            'transition-colors',
            'duration-200'
          );
          // Add an onclick event to the row
          row.onclick = function() {
            redirectToTask(task);
          };
          row.innerHTML = `
            <td class="px-2 py-1 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
              ${task.name}
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
      renderTaskPaginationControls(tasks);
    }

    // Render pagination controls for tasks using arrow symbols
    function renderTaskPaginationControls(tasks) {
      const paginationDiv = document.getElementById('task-pagination-controls');
      paginationDiv.innerHTML = '';

      const totalPages = Math.ceil(tasks.length / itemsPerPage);
      if (totalPages <= 1) return; // No pagination needed

      // Previous arrow button
      const prevButton = document.createElement('button');
      prevButton.textContent = '←';
      prevButton.className = 'px-3 py-1 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded';
      prevButton.disabled = currentPage === 1;
      prevButton.addEventListener('click', () => {
        if (currentPage > 1) {
          currentPage--;
          renderTasks(tasks);
        }
      });
      paginationDiv.appendChild(prevButton);

      // Page info
      const pageInfo = document.createElement('span');
      pageInfo.textContent = `${currentPage} of ${totalPages}`;
      paginationDiv.appendChild(pageInfo);

      // Next arrow button
      const nextButton = document.createElement('button');
      nextButton.textContent = '→';
      nextButton.className = 'px-3 py-1 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded';
      nextButton.disabled = currentPage === totalPages;
      nextButton.addEventListener('click', () => {
        if (currentPage < totalPages) {
          currentPage++;
          renderTasks(tasks);
        }
      });
      paginationDiv.appendChild(nextButton);
    }

    // Fetch tasks from the server
    async function loadTasks() {
      try {
        const response = await fetch('/tasks/all');
        tasksList = await response.json();
        currentPage = 1; // Reset pagination
        renderTasks(tasksList);
      } catch (error) {
        console.error('Error loading tasks:', error);
        const taskTableBody = document.getElementById('taskTableBody');
        taskTableBody.textContent = 'Error loading tasks!';
      }
    }

    // Store task data in localStorage and redirect to the full task view
    function redirectToTask(task) {
      localStorage.setItem('task', JSON.stringify(task));
      window.location.href = '/task-detail';
    }

    // Search functionality: filter tasks by name (case-insensitive) and paginate the results
    document.getElementById('taskSearch').addEventListener('input', function() {
      const query = this.value.toLowerCase();
      const filteredTasks = tasksList.filter(task => task.name.toLowerCase().includes(query));
      currentPage = 1; // Reset to first page for new search results
      renderTasks(filteredTasks);
    });

    updateButton();
    loadTasks();
  </script>
</x-app-layout>