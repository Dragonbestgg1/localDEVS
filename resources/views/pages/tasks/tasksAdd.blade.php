<x-app-layout>
    <button id="backButton">Atpakaļ</button>

    <h1>Izveidot uzdevumu</h1>
    <form id="taskForm">
        @csrf
        <label for="code">Kods:</label>
        <input type="text" id="code" name="code" required><br><br>
        
        <label for="name">Nosaukums:</label>
        <input type="text" id="name" name="name" required><br><br>
        
        <label for="time_limit">Laika Limits (sekundēs):</label>
        <input type="number" id="time_limit" name="time_limit" required><br><br>
        
        <label for="memory_limit">Atmiņas limits (MB):</label>
        <input type="text" id="memory_limit" name="memory_limit" required pattern="^\d+(\.\d{1,2})?$"><br><br>
        
        <label for="definition">Definīcija:</label>
        <textarea id="definition" name="definition" required></textarea><br><br>
        
        <label for="input_definition">Ievade:</label>
        <textarea id="input_definition" name="input_definition" required></textarea><br><br>
        
        <label for="output_definition">Izvade:</label>
        <textarea id="output_definition" name="output_definition" required></textarea><br><br>
        
        <label for="examples">Piemēri:</label>
        <textarea id="examples" name="examples"></textarea><br><br>
        
        <label for="correct_answer">Pareizā atbilde:</label>
        <input type="text" id="correct_answer" name="correct_answer" required><br><br>
        
        <button type="submit">Izveidot</button>
    </form>
    
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
