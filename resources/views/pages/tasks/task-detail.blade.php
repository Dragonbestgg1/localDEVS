<x-app-layout>
    <h1>Task Detail</h1>
    <div id="taskDetail">Loading task data...</div>

    <script>
        function formatTime(seconds) {
            const hrs = Math.floor(seconds / 3600);
            const mins = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            return `${hrs}h ${mins}m ${secs}s`;
        }

        function loadTaskData() {
            const taskData = localStorage.getItem('task');
            if (taskData) {
                const task = JSON.parse(taskData);
                const taskDetailDiv = document.getElementById('taskDetail');

                taskDetailDiv.innerHTML = `
                    <p><strong>Name:</strong> ${task.name}</p>
                    <p><strong>Code:</strong> ${task.code}</p>
                    <p><strong>Time limit:</strong> ${formatTime(task.time_limit)}</p>
                    <p><strong>Memory Limit</strong> ${task.memory_limit}</p>
                    <p><strong>Description:</strong> ${task.definition}</p>
                    <p><strong>Input</strong> ${task.input_definition}</p>
                    <p><strong>Output</strong> ${task.output_definition}</p>
                    ${task.examples ? `<p><strong>Examples:</strong> ${task.examples}</p>` : ''}
                `;
            } else {
                document.getElementById('taskDetail').textContent = 'No task data found.';
            }
        }

        loadTaskData();
    </script>
</x-app-layout>
