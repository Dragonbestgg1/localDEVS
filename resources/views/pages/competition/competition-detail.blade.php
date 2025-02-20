<x-app-layout>
    <x-slot name="header">
        <!-- Header with Title and Back Link using x-nav-link -->
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl sm:text-lg text-gray-800 dark:text-gray-200 leading-tight">
                Competition Details
            </h2>
            <a href="{{ route('competition') }}"
                id="backButton"
                class="text-sm mb-4 px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded focus:outline-none focus:ring-2 focus:ring-gray-500">
                Atpakaļ
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div id="competition-details" class="text-gray-900 dark:text-gray-100 text-sm space-y-6">
                <!-- Competition details will be populated here using JS -->
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        // Function to format seconds into hours, minutes, and seconds
        function formatTime(seconds) {
            const hrs = Math.floor(seconds / 3600);
            const mins = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            return `${hrs}h ${mins}m ${secs}s`;
        }

        function loadCompetitionDetails() {
            // Retrieve the competition data from localStorage
            const competition = JSON.parse(localStorage.getItem('competition'));

            if (!competition) {
                document.getElementById('competition-details').textContent = 'No competition data available.';
                return;
            }

            // Build the HTML for competition fields
            const detailsHTML = `
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><strong>Name:</strong> ${competition.name}</div>
                    <div><strong>Time:</strong> ${competition.time}</div>
                    <div><strong>From:</strong> ${competition.from}</div>
                    <div><strong>Till:</strong> ${competition.till}</div>
                    <div class="sm:col-span-2"><strong>Description:</strong> ${competition.description}</div>
                    <div class="sm:col-span-2"><strong>Information:</strong> ${competition.information}</div>
                    <div><strong>Difficulty:</strong> ${competition.difficulty}</div>
                </div>
            `;

            // Build the HTML for tasks
            let tasksHTML = '';
            if (competition.tasks && competition.tasks.length > 0) {
                tasksHTML = `
                    <div class="space-y-4">
                        ${competition.tasks.map(task => `
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded shadow">
                                <p><strong>Name:</strong> ${task.name}</p>
                                <p><strong>Code:</strong> ${task.code}</p>
                                <p><strong>Completions:</strong> ${task.completions}</p>
                                <p><strong>Submissions:</strong> ${task.submitions}</p>
                                <p><strong>Time Limit:</strong> ${formatTime(task.time_limit)}</p>
                                <p><strong>Memory Limit:</strong> ${task.memory_limit}</p>
                                <p><strong>Definition:</strong> ${task.definition}</p>
                                <p><strong>Input Definition:</strong> ${task.input_definition}</p>
                                <p><strong>Output Definition:</strong> ${task.output_definition}</p>
                                ${task.examples ? `<p><strong>Examples:</strong> ${task.examples}</p>` : ''}
                            </div>
                        `).join('')}
                    </div>
                `;
            } else {
                tasksHTML = `<p>No tasks available for this competition.</p>`;
            }

            // Combine competition details and tasks
            document.getElementById('competition-details').innerHTML = `
                <div class="space-y-6">
                    <div class="space-y-2">
                        ${detailsHTML}
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold">Tasks</h3>
                        ${tasksHTML}
                    </div>
                </div>
            `;
        }

        loadCompetitionDetails();
    </script>
</x-app-layout>