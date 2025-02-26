<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-xl sm:text-3xl text-gray-800 dark:text-gray-200 leading-tight">
        Task Detail
      </h2>
      <button id="backButton" class="text-base px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded shadow hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500">
        Atpakaļ
      </button>
    </div>
  </x-slot>

  <div class="max-w-7xl mx-auto px-2">
    <!-- Task Detail Card -->
    <div id="taskDetail" class="overflow-hidden p-6">
      <p class="text-center text-sm sm:text-base text-gray-500 dark:text-gray-300">
        Loading task data...
      </p>
    </div>
  </div>

  <script>
    function formatTime(seconds) {
      const hrs = Math.floor(seconds / 3600);
      const mins = Math.floor((seconds % 3600) / 60);
      const secs = seconds % 60;
      return `${hrs}h ${mins}m ${secs}s`;
    }

    function loadTaskData() {
      const taskData = localStorage.getItem('task');
      const taskCard = document.querySelector('#taskDetail');

      if (taskData) {
        const task = JSON.parse(taskData);

        taskCard.innerHTML = `
          <!-- Short Fields: arranged in a two-column grid -->
          <div class="grid grid-cols-2 gap-x-8 gap-y-4">
            <!-- Name -->
            <div>
              <p class="font-medium text-lg	text-gray-600 dark:text-gray-400">Name:</p>
              <p class="text-gray-900 dark:text-gray-100">${task.name}</p>
            </div>
            <!-- Code -->
            <div>
              <p class="font-medium text-lg	text-gray-600 dark:text-gray-400">Code:</p>
              <p class="text-gray-900 dark:text-gray-100">${task.code}</p>
            </div>
            <!-- Time Limit -->
            <div>
              <p class="font-medium text-lg text-gray-600 dark:text-gray-400">Time limit:</p>
              <p class="text-gray-900 dark:text-gray-100">${formatTime(task.time_limit)}</p>
            </div>
            <!-- Memory Limit -->
            <div>
              <p class="font-medium text-lg text-gray-600 dark:text-gray-400">Memory Limit:</p>
              <p class="text-gray-900 dark:text-gray-100">${task.memory_limit}</p>
            </div>
            <!-- Input -->
            <div class="flex items-center">
              <p class="font-medium text-lg text-gray-600 dark:text-gray-400 mr-2">Input:</p>
              <p class="text-gray-900 dark:text-gray-100">${task.input_definition}</p>
            </div>
            <!-- Output -->
            <div class="flex items-center">
              <p class="font-medium text-lg text-gray-600 dark:text-gray-400 mr-2">Output:</p>
              <p class="text-gray-900 dark:text-gray-100">${task.output_definition}</p>
            </div>
          </div>

          <!-- Full-width Fields: placed below the grid -->
          <div class="mt-6 space-y-4">
            <!-- Description -->
            <div>
              <p class="font-medium text-lg text-gray-600 dark:text-gray-400">Description:</p>
              <p class="text-gray-900 dark:text-gray-100">${task.definition}</p>
            </div>
            <!-- Examples (if available) -->
            ${
              task.examples
                ? `
                  <div>
                    <p class="font-medium text-lg text-gray-600 dark:text-gray-400">Examples:</p>
                    <p class="text-gray-900 dark:text-gray-100">${task.examples}</p>
                  </div>
                `
                : ''
            }
          </div>
        `;
      } else {
        taskCard.textContent = 'No task data found.';
      }
    }

    // Back button: Navigate back to the tasks list when clicked
    document.getElementById('backButton').addEventListener('click', () => {
      window.location.href = '/tasks'; // Adjust URL if needed
    });

    loadTaskData();
  </script>
</x-app-layout>