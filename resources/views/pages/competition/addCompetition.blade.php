<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<x-app-layout>
    <form action="{{ route('competitions.store') }}" method="POST">
        @csrf
        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div>
            <label for="time">Duration:</label>
            <input type="text" id="time" name="time" placeholder="e.g., 2h 30m">
        </div>
        
        <div>
            <label for="from">From:</label>
            <input type="datetime-local" id="from" name="from">
        </div>
        <div>
            <label for="till">Till:</label>
            <input type="datetime-local" id="till" name="till">
        </div>
        <div>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
        </div>
        <div>
            <label for="information">Information:</label>
            <textarea id="information" name="information" required></textarea>
        </div>
        <div>
            <label for="difficulty">Difficulty:</label>
            <select id="difficulty" name="difficulty" required>
                <option value="easy">Easy</option>
                <option value="medium">Medium</option>
                <option value="hard">Hard</option>
            </select>
        </div>
        
        <div>
            <label for="tasks">Tasks:</label>
            <div id="tasks">
                <!-- Checkboxes will be populated by jQuery -->
            </div>
        </div>
        <div>
            <button type="submit">Submit</button>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            $.ajax({
                url: '{{ url('/tasks/all') }}',
                type: 'GET',
                success: function(response) {
                    var tasksDiv = $('#tasks');
                    response.forEach(function(task) {
                        tasksDiv.append('<div><input type="checkbox" name="tasks[]" value="' +
                            task.id + '">' + task.name + '</div>');
                    });
                }
            });
        });
    </script>
</x-app-layout>
