<x-app-layout>
  <x-slot name="header">
    <h2 class="leading-tight text-gray-800 dark:text-gray-200">
      Izveidot uzdevumu
    </h2>
  </x-slot>

  <div class="max-w-7xl mx-auto px-4 py-6">
    <!-- Back Button -->
    <button id="backButton" class="mb-4 px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded focus:outline-none focus:ring-2 focus:ring-gray-500">
      Atpakaļ
    </button>

    <!-- Task Creation Form -->
    <form id="taskForm">
      @csrf

      <div class="mb-4">
        <label for="code" class="block mb-1 text-gray-700 dark:text-gray-300">Kods:</label>
        <input type="text" id="code" name="code" required class="w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-gray-500">
      </div>

      <div class="mb-4">
        <label for="name" class="block mb-1 text-gray-700 dark:text-gray-300">Nosaukums:</label>
        <input type="text" id="name" name="name" required class="w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-gray-500">
      </div>

      <div class="mb-4">
        <label for="time_limit" class="block mb-1 text-gray-700 dark:text-gray-300">Laika Limits (sekundēs):</label>
        <input type="number" id="time_limit" name="time_limit" required class="w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-gray-500">
      </div>

      <div class="mb-4">
        <label for="memory_limit" class="block mb-1 text-gray-700 dark:text-gray-300">Atmiņas limits (MB):</label>
        <input type="text" id="memory_limit" name="memory_limit" required pattern="^\d+(\.\d{1,2})?$" class="w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-gray-500">
      </div>

      <div class="mb-4">
        <label for="definition" class="block mb-1 text-gray-700 dark:text-gray-300">Definīcija:</label>
        <textarea id="definition" name="definition" required class="w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-gray-500 resize-none"></textarea>
      </div>

      <div class="mb-4">
        <label for="input_definition" class="block mb-1 text-gray-700 dark:text-gray-300">Ievade:</label>
        <textarea id="input_definition" name="input_definition" required class="w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-gray-500 resize-none"></textarea>
      </div>

      <div class="mb-4">
        <label for="output_definition" class="block mb-1 text-gray-700 dark:text-gray-300">Izvade:</label>
        <textarea id="output_definition" name="output_definition" required class="w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-gray-500 resize-none"></textarea>
      </div>

      <div class="mb-4">
        <label for="examples" class="block mb-1 text-gray-700 dark:text-gray-300">Piemēri:</label>
        <textarea id="examples" name="examples" class="w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-gray-500 resize-none"></textarea>
      </div>

      <div class="mb-4">
        <label for="correct_answer" class="block mb-1 text-gray-700 dark:text-gray-300">Pareizā atbilde:</label>
        <input type="text" id="correct_answer" name="correct_answer" required class="w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-gray-500">
      </div>

      <button type="submit" class="w-full px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded focus:outline-none focus:ring-2 focus:ring-gray-500">
        Izveidot
      </button>
    </form>
  </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#taskForm').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            $.ajax({
                url: '/submit-task',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    alert('Uzdevums veiksmīgi izveidots!');
                    window.location.href = '/tasks';
                },
                error: function(xhr) {
                    alert('Kļūda, iesniedzot uzdevumu!');
                }
            });
        });

        document.getElementById('backButton').addEventListener('click', () => {
            window.location.href = '/tasks';
        });
    </script>
</x-app-layout>
