<x-app-layout>
    <button id="routeButton">Loading...</button>

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
        document.addEventListener('DOMContentLoaded', function () {
            updateButton();
            loadCompetitions();
        });

        async function updateButton() {
            try {
                const response = await fetch('/get-class');
                const data = await response.json();

                const button = document.getElementById('routeButton');
                if (!button) {
                    console.error('Button not found');
                    return;
                }

                button.style.display = 'block';

                if (data.class) {
                    if (data.class === 'teacher') {
                        button.textContent = 'Uzsākt sacensības';
                        button.addEventListener('click', () => {
                            window.location.href = '/competition/addCompetition';
                        });
                    } else {
                        button.style.display = 'none';
                    }
                } else {
                    button.textContent = 'Class not found!';
                }
            } catch (error) {
                console.error('Error fetching class:', error);
                const button = document.getElementById('routeButton');
                if (button) {
                    button.textContent = 'Error loading class!';
                }
            }
        }

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
                            const tasks = competition.tasks.map(task => task.name).join(', ');  // Assuming 'name' is a property of the task
                            const row = document.createElement('tr');

                            row.innerHTML = `
                                <td><a href="#" onclick='redirectToCompetition(${JSON.stringify(competition)})'>${competition.name}</a></td>
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

        function redirectToCompetition(competition) {
            // Store the competition data in localStorage
            localStorage.setItem('competition', JSON.stringify(competition));
            // Redirect to the competition detail page
            window.location.href = '/competition-detail';
        }
    </script>
</x-app-layout>
