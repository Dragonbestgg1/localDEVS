<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl sm:text-lg text-gray-800 dark:text-gray-200 leading-tight">
      All Competitions
    </h2>
  </x-slot>

  <style>
  /* Base scrollbar styling (for non-dark mode) */
  ::-webkit-scrollbar {
    width: 8px;
  }
  ::-webkit-scrollbar-track {
    background: #f1f1f1;
  }
  ::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 4px;
  }

  /* Force dark scrollbar styling when dark mode is active.
     Even though dark mode elsewhere gives white backgrounds (via dark:bg-white),
     we want the scrollbar elements to have dark colors. */
  html.dark ::-webkit-scrollbar-track {
    background: #2d3748 !important;
  }
  html.dark ::-webkit-scrollbar-thumb {
    background-color: #4a5568 !important;
  }

  /* Firefox scrollbar styling */
  html {
    scrollbar-width: thin;
    scrollbar-color: #888 #f1f1f1;
  }
  html.dark {
    scrollbar-color: #4a5568 #2d3748 !important;
  }
</style>

  <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
    <div class="overflow-hidden shadow-sm p-2 sm:p-6">
      <!-- Action Button Container -->
      <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-4">
        <button
          id="routeButton"
          class="px-2 py-1 sm:px-4 sm:py-2 text-xs sm:text-sm bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 rounded shadow">
          Loading...
        </button>
      </div>

      <!-- Competitions Table -->
      <div class="overflow-x-auto">
        <table class="w-full min-w-full border-separate border-spacing-y-2">
          <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
              <th scope="col" class="px-2 py-1 sm:px-6 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-200 tracking-wider">
                Name
              </th>
              <th scope="col" class="px-2 py-1 sm:px-6 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-200 tracking-wider">
                Time
              </th>
              <th scope="col" class="px-2 py-1 sm:px-6 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-200 tracking-wider">
                From
              </th>
              <th scope="col" class="px-2 py-1 sm:px-6 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-200 tracking-wider">
                Till
              </th>
              <th scope="col" class="px-2 py-1 sm:px-6 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-200 tracking-wider">
                Description
              </th>
              <th scope="col" class="px-2 py-1 sm:px-6 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-200 tracking-wider">
                Information
              </th>
              <th scope="col" class="px-2 py-1 sm:px-6 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-200 tracking-wider">
                Difficulty
              </th>
              <th scope="col" class="px-2 py-1 sm:px-6 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-200 tracking-wider">
                Tasks
              </th>
            </tr>
          </thead>
          <tbody id="competition-table-body" class="bg-white dark:bg-gray-800">
            <tr>
              <td colspan="8" class="px-2 py-1 sm:px-6 sm:py-4 text-center text-xs sm:text-sm text-gray-500 dark:text-gray-300">
                Loading competitions...
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- jQuery and Script -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    // Helper function to limit text length to maxLength (default is 10 characters)
    function truncate(text, maxLength = 10) {
      return text && text.length > maxLength ? text.substring(0, maxLength) : text;
    }

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
          competitionTableBody.innerHTML = '';

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
              
              // Truncate description and information to 10 characters
              const truncatedDescription = truncate(competition.description, 10);
              const truncatedInformation = truncate(competition.information, 10);

              const row = document.createElement('tr');
              row.classList.add(
                'hover:bg-gray-100',
                'dark:hover:bg-gray-700',
                'transition-colors',
                'duration-200'
              );
              row.innerHTML = `
                <td class="px-2 py-1 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                  <a href="#" onclick='redirectToCompetition(${JSON.stringify(competition)})'
                     class="text-gray-900 dark:text-gray-200 hover:underline font-semibold">
                    ${competition.name}
                  </a>
                </td>
                <td class="px-2 py-1 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                  ${competition.time}
                </td>
                <td class="px-2 py-1 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                  ${competition.from}
                </td>
                <td class="px-2 py-1 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                  ${competition.till}
                </td>
                <td class="px-2 py-1 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                  ${truncatedDescription}
                </td>
                <td class="px-2 py-1 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                  ${truncatedInformation}
                </td>
                <td class="px-2 py-1 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                  ${competition.difficulty}
                </td>
                <td class="px-2 py-1 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                  ${tasks}
                </td>
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
      localStorage.setItem('competition', JSON.stringify(competition));
      window.location.href = '/competition-detail';
    }
  </script>
</x-app-layout>