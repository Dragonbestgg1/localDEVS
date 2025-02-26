<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between w-full">
      <h2 class="font-semibold text-xl sm:text-3xl text-gray-800 dark:text-gray-200 leading-tight">
        Izveidot sacensību
      </h2>
      <button onclick="window.location.href='{{ route('competition') }}'"
        id="backButton"
        class="text-base mb-4 px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded focus:outline-none focus:ring-2 focus:ring-gray-500">
        Atpakaļ
      </button>
    </div>
  </x-slot>

  <div class="max-w-7xl mx-auto px-4">

    <!-- Competition Creation Form -->
    <form action="{{ route('competitions.store') }}" method="POST" class="p-6">
      @csrf

      <!-- Name -->
      <div class="mb-4">
        <label for="name" class="block mb-1 text-lg text-gray-700 dark:text-gray-300">Name:</label>
        <input
          type="text"
          id="name"
          name="name"
          required
          class="w-full p-2 text-sm sm:text-base bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
      </div>

      <!-- Duration -->
      <div class="mb-4">
        <label for="time" class="block mb-1 text-lg text-gray-700 dark:text-gray-300">Duration:</label>
        <input
          type="text"
          id="time"
          name="time"
          placeholder="e.g., 2h 30m"
          class="w-full p-2 text-sm sm:text-base bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
      </div>

      <!-- From -->
      <div class="mb-4">
        <label for="from" class="block mb-1 text-lg text-gray-700 dark:text-gray-300">From:</label>
        <input
          type="datetime-local"
          id="from"
          name="from"
          class="w-full p-2 text-sm sm:text-base bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
      </div>

      <!-- Till -->
      <div class="mb-4">
        <label for="till" class="block mb-1 text-lg text-gray-700 dark:text-gray-300">Till:</label>
        <input
          type="datetime-local"
          id="till"
          name="till"
          class="w-full p-2 text-sm sm:text-base bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
      </div>

      <!-- Description -->
      <div class="mb-4">
        <label for="description" class="block mb-1 text-lg text-gray-700 dark:text-gray-300">Description:</label>
        <textarea
          id="description"
          name="description"
          required
          class="w-full p-2 text-sm sm:text-base bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 resize-none"></textarea>
      </div>

      <!-- Information -->
      <div class="mb-4">
        <label for="information" class="block mb-1 text-lg text-gray-700 dark:text-gray-300">Information:</label>
        <textarea
          id="information"
          name="information"
          required
          class="w-full p-2 text-sm sm:text-base bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 resize-none"></textarea>
      </div>

      <!-- Difficulty -->
      <div class="mb-4">
        <label for="difficulty" class="block mb-1 text-lg text-gray-700 dark:text-gray-300">Difficulty:</label>
        <select
          id="difficulty"
          name="difficulty"
          required
          class="w-full p-2 text-sm sm:text-base bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
          <option value="easy">Easy</option>
          <option value="medium">Medium</option>
          <option value="hard">Hard</option>
        </select>
      </div>

      <!-- Tasks with Search and Pagination -->
      <div class="mb-4">
        <label for="tasks" class="block mb-1 text-lg text-gray-700 dark:text-gray-300">Tasks:</label>
        <!-- Search Bar -->
        <input type="text" id="taskSearch" placeholder="Search tasks"
          class="w-full p-2 mb-2 text-sm sm:text-base bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
        <!-- Tasks grid -->
        <div id="tasks" class="grid grid-cols-3 gap-4"></div>
        <!-- Pagination controls -->
        <div id="pagination" class="mt-4 flex justify-center"></div>
      </div>

      <!-- Submit Button -->
      <div class="mt-4">
        <button
          type="submit"
          class="w-full px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded focus:outline-none focus:ring-2 focus:ring-gray-500">
          Submit
        </button>
      </div>
    </form>
  </div>

  <!-- jQuery (if not already loaded) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    // Optional: Back button (already handled via onclick, but you can keep this if needed)
    document.getElementById('backButton').addEventListener('click', () => {
      window.location.href = "{{ route('competition') }}";
    });

    // Variables to hold tasks data and pagination state
    let tasksData = [];
    let filteredTasks = [];
    let currentPage = 0;
    const tasksPerPage = 27;

    // Render the tasks for the current page
    function renderTasks() {
      const tasksDiv = $('#tasks');
      tasksDiv.empty();
      const start = currentPage * tasksPerPage;
      const end = start + tasksPerPage;
      const tasksToShow = filteredTasks.slice(start, end);
      tasksToShow.forEach(function(task) {
        tasksDiv.append(`
          <label class="flex items-center space-x-2">
            <input type="checkbox" name="tasks[]" value="${task.id}" class="form-checkbox h-5 w-5 rounded">
            <span class="text-gray-900 dark:text-gray-100">${task.name}</span>
          </label>
        `);
      });
    }

    // Render pagination controls if needed
    function renderPagination() {
      const paginationDiv = $('#pagination');
      paginationDiv.empty();
      const totalPages = Math.ceil(filteredTasks.length / tasksPerPage);
      if (totalPages <= 1) return; // No pagination needed

      // Previous button
      if (currentPage > 0) {
        paginationDiv.append('<button id="prevPage" class="px-3 py-1 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded mr-2">Previous</button>');
      }
      // Current page indicator
      paginationDiv.append(`<span class="px-3 py-1">${currentPage + 1} of ${totalPages}</span>`);
      // Next button
      if (currentPage < totalPages - 1) {
        paginationDiv.append('<button id="nextPage" class="px-3 py-1 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded ml-2">Next</button>');
      }
    }

    // Filter tasks based on the search query and update the view
    function filterTasks(query) {
      if (query === '') {
        filteredTasks = tasksData;
      } else {
        filteredTasks = tasksData.filter(task => task.name.toLowerCase().includes(query.toLowerCase()));
      }
      currentPage = 0;
      renderTasks();
      renderPagination();
    }

    $(document).ready(function() {
      // Search input event
      $('#taskSearch').on('input', function() {
        const query = $(this).val();
        filterTasks(query);
      });

      // Fetch tasks via AJAX
      $.ajax({
        url: '{{ url('/tasks/all') }}',
        type: 'GET',
        success: function(response) {
          tasksData = response;
          filteredTasks = tasksData; // Initially, no filtering
          renderTasks();
          renderPagination();
        },
        error: function() {
          $('#tasks').text('Unable to load tasks.');
        }
      });

      // Delegate pagination button events
      $('#pagination').on('click', '#prevPage', function() {
        if (currentPage > 0) {
          currentPage--;
          renderTasks();
          renderPagination();
        }
      });

      $('#pagination').on('click', '#nextPage', function() {
        const totalPages = Math.ceil(filteredTasks.length / tasksPerPage);
        if (currentPage < totalPages - 1) {
          currentPage++;
          renderTasks();
          renderPagination();
        }
      });
    });
  </script>
</x-app-layout>