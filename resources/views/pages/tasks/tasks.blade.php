<x-app-layout>
    <button id="routeButton">Loading...</button>
    
    <h1>Visi Uzdevumi</h1>
    <table id="taskTable">
        <thead>
            <tr>
                <th>Task Name</th>
                <th>Code</th>
            </tr>
        </thead>
        <tbody id="taskTableBody">Loading tasks...</tbody>
    </table>

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

        updateButton();
        loadTasks();
    </script>
</x-app-layout>
