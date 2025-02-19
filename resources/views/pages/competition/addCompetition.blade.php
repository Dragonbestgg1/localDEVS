<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl sm:text-lg text-gray-800 dark:text-gray-200 leading-tight">
      Izveidot sacensību
    </h2>
  </x-slot>

  <div class="max-w-7xl mx-auto px-4">
    <!-- Back Button (optional) -->
    <button onclick="window.location.href='{{ route('competition') }}'"
      id="backButton"
      class="text-sm mb-4 px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded focus:outline-none focus:ring-2 focus:ring-gray-500">
      Atpakaļ
    </button>

    <!-- Competition Creation Form -->
    <form action="{{ route('competitions.store') }}" method="POST" class="p-6">
      @csrf

      <!-- Name -->
      <div class="mb-4">
        <label for="name" class="block mb-1 text-gray-700 dark:text-gray-300">Name:</label>
        <input
          type="text"
          id="name"
          name="name"
          required
          class="w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100
                 border border-gray-300 dark:border-gray-600 rounded focus:outline-none
                 focus:ring-2 focus:ring-gray-500">
      </div>

      <!-- Duration -->
      <div class="mb-4">
        <label for="time" class="block mb-1 text-gray-700 dark:text-gray-300">Duration:</label>
        <input
          type="text"
          id="time"
          name="time"
          placeholder="e.g., 2h 30m"
          class="w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100
                 border border-gray-300 dark:border-gray-600 rounded focus:outline-none
                 focus:ring-2 focus:ring-gray-500">
      </div>

      <!-- From -->
      <div class="mb-4">
        <label for="from" class="block mb-1 text-gray-700 dark:text-gray-300">From:</label>
        <input
          type="datetime-local"
          id="from"
          name="from"
          class="w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100
                 border border-gray-300 dark:border-gray-600 rounded focus:outline-none
                 focus:ring-2 focus:ring-gray-500">
      </div>

      <!-- Till -->
      <div class="mb-4">
        <label for="till" class="block mb-1 text-gray-700 dark:text-gray-300">Till:</label>
        <input
          type="datetime-local"
          id="till"
          name="till"
          class="w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100
                 border border-gray-300 dark:border-gray-600 rounded focus:outline-none
                 focus:ring-2 focus:ring-gray-500">
      </div>

      <!-- Description -->
      <div class="mb-4">
        <label for="description" class="block mb-1 text-gray-700 dark:text-gray-300">Description:</label>
        <textarea
          id="description"
          name="description"
          required
          class="w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100
                 border border-gray-300 dark:border-gray-600 rounded focus:outline-none
                 focus:ring-2 focus:ring-gray-500 resize-none"></textarea>
      </div>

      <!-- Information -->
      <div class="mb-4">
        <label for="information" class="block mb-1 text-gray-700 dark:text-gray-300">Information:</label>
        <textarea
          id="information"
          name="information"
          required
          class="w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100
                 border border-gray-300 dark:border-gray-600 rounded focus:outline-none
                 focus:ring-2 focus:ring-gray-500 resize-none"></textarea>
      </div>

      <!-- Difficulty -->
      <div class="mb-4">
        <label for="difficulty" class="block mb-1 text-gray-700 dark:text-gray-300">Difficulty:</label>
        <select
          id="difficulty"
          name="difficulty"
          required
          class="w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100
                 border border-gray-300 dark:border-gray-600 rounded focus:outline-none
                 focus:ring-2 focus:ring-gray-500">
          <option value="easy">Easy</option>
          <option value="medium">Medium</option>
          <option value="hard">Hard</option>
        </select>
      </div>

      <!-- Tasks (3 per row) -->
      <div class="mb-4">
        <label for="tasks" class="block mb-1 text-gray-700 dark:text-gray-300">Tasks:</label>
        <!-- Use a grid with 3 columns for the checkboxes -->
        <div id="tasks" class="grid grid-cols-3 gap-4">
          <!-- Checkboxes will be populated by jQuery -->
        </div>
      </div>

      <!-- Submit Button -->
      <div class="mt-4">
        <button
          type="submit"
          class="w-full px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-100
                 rounded focus:outline-none focus:ring-2 focus:ring-gray-500">
          Submit
        </button>
      </div>
    </form>
  </div>

  <!-- jQuery (if not already loaded) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    // Optional: Back button
    document.getElementById('backButton').addEventListener('click', () => {
      window.location.href = "{{ route('competition') }}"; // Adjust route name if needed
    });

    // Populate tasks checkboxes via AJAX
    $(document).ready(function() {
      $.ajax({
        url: '{{ url('/tasks/all') }}',  // Corrected URL
        type: 'GET',
        success: function(response) {
          const tasksDiv = $('#tasks');
          response.forEach(function(task) {
            tasksDiv.append(`
              <label class="flex items-center space-x-2">
                <input
                  type="checkbox"
                  name="tasks[]"
                  value="${task.id}"
                  class="form-checkbox h-5 w-5 text-indigo-600 rounded"
                >
                <span class="text-gray-900 dark:text-gray-100">${task.name}</span>
              </label>
            `);
          });
        },
        error: function() {
          $('#tasks').text('Unable to load tasks.');
        }
      });
    });
  </script>
</x-app-layout>