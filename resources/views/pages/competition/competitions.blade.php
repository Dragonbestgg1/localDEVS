<x-app-layout>
    <h1>All Competitions</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Time</th>
                <th>From</th>
                <th>Till</th>
                <th>Description</th>
                <th>Information</th>
                <th>Difficulty</th>
                <th>Tasks</th>
            </tr>
        </thead>
        <tbody id="competition-table-body">
            <!-- Data will be populated here using JS -->
        </tbody>
    </table>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function loadCompetitions() {
            $.ajax({
                url: '/competitions/all',
                type: 'GET',
                dataType: 'json',
                success: function(competitions) {
                    const competitionTableBody = document.getElementById('competition-table-body');
                    competitionTableBody.innerHTML = ''; // Clear the table body

                    if (competitions.length === 0) {
                        const row = document.createElement('tr');
                        const cell = document.createElement('td');
                        cell.colSpan = 8;
                        cell.textContent = 'No competitions available.';
                        row.appendChild(cell);
                        competitionTableBody.appendChild(row);
                    } else {
                        competitions.forEach(function(competition) {
                            const tasks = competition.tasks.map(task => task.name).join(', ');
                            const row = document.createElement('tr');

                            row.innerHTML = `
                                <td>${competition.name}</td>
                                <td>${competition.time}</td>
                                <td>${competition.from}</td>
                                <td>${competition.till}</td>
                                <td>${competition.description}</td>
                                <td>${competition.information}</td>
                                <td>${competition.difficulty}</td>
                                <td>${tasks}</td>
                            `;

                            competitionTableBody.appendChild(row);
                        });
                    }
                },
                error: function() {
                    console.error('Error loading competitions');
                    const competitionTableBody = document.getElementById('competition-table-body');
                    const row = document.createElement('tr');
                    const cell = document.createElement('td');
                    cell.colSpan = 8;
                    cell.textContent = 'Error loading competitions!';
                    row.appendChild(cell);
                    competitionTableBody.appendChild(row);
                }
            });
        }

        loadCompetitions();
    </script>
</x-app-layout>
