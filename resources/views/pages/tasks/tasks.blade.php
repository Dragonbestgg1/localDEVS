<x-app-layout>
    <button id="routeButton">Loading...</button>
    
    <h1>Visi Uzdevumi</h1>
    <ul id="taskList">Loading tasks...</ul>

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

                const taskList = document.getElementById('taskList');
                taskList.innerHTML = ''; // Clear the loading text

                if (tasks.length === 0) {
                    taskList.textContent = 'No tasks available.';
                } else {
                    tasks.forEach(task => {
                        const li = document.createElement('li');
                        li.textContent = `${task.name} (Code: ${task.code})`;
                        taskList.appendChild(li);
                    });
                }
            } catch (error) {
                console.error('Error loading tasks:', error);
                const taskList = document.getElementById('taskList');
                taskList.textContent = 'Error loading tasks!';
            }
        }

        updateButton();
        loadTasks();
    </script>
</x-app-layout>
