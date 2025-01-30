<x-app-layout>
    <h1>Competition Details</h1>
    <div id="competition-details">
        <!-- Competition details will be populated here using JS -->
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function loadCompetitionDetails() {
            // Retrieve the competition data from localStorage
            const competition = JSON.parse(localStorage.getItem('competition'));

            if (!competition) {
                document.getElementById('competition-details').textContent = 'No competition data available.';
                return;
            }

            // Display the competition data
            const competitionDetails = document.getElementById('competition-details');
            competitionDetails.innerHTML = `
                <p><strong>Name:</strong> ${competition.name}</p>
                <p><strong>Time:</strong> ${competition.time}</p>
                <p><strong>From:</strong> ${competition.from}</p>
                <p><strong>Till:</strong> ${competition.till}</p>
                <p><strong>Description:</strong> ${competition.description}</p>
                <p><strong>Information:</strong> ${competition.information}</p>
                <p><strong>Difficulty:</strong> ${competition.difficulty}</p>
                <p><strong>Tasks:</strong><br> 
                ${competition.tasks.map(task => `
                    <p><strong>Name:</strong> ${task.name}</p>
                    <p><strong>Code:</strong> ${task.code}</p>
                    <p><strong>Completions:</strong> ${task.completions}</p>
                    <p><strong>Submissions:</strong> ${task.submitions}</p>
                    <p><strong>Time Limit:</strong> ${task.time_limit}</p>
                    <p><strong>Memory Limit:</strong> ${task.memory_limit}</p>
                    <p><strong>Definition:</strong> ${task.definition}</p>
                    <p><strong>Input Definition:</strong> ${task.input_definition}</p>
                    <p><strong>Output Definition:</strong> ${task.output_definition}</p>
                    ${task.examples ? `<p><strong>Examples:</strong> ${task.examples}</p>` : ''}
                `).join('<br>')}
                </p>
            `;
        }

        loadCompetitionDetails();
    </script>
</x-app-layout>
