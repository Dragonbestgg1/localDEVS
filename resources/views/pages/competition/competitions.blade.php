<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl sm:text-lg text-gray-800 dark:text-gray-200 leading-tight">
      All Competitions
    </h2>
  </x-slot>
  
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

      <!-- Pagination Controls -->
      <div id="pagination-controls" class="mt-4 flex justify-center items-center space-x-4"></div>
    </div>
  </div>

  <!-- jQuery and Script -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    // Helper function to truncate text to a specified maxLength and add ellipsis if needed
    function truncate(text, maxLength = 10) {
      if (text && text.length > maxLength) {
        return text.substring(0, maxLength) + '...';
      }
      return text;
    }

    let competitionsList = [];
    let currentPage = 1;
    const itemsPerPage = 10;

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

    // Render competitions based on currentPage
    function renderCompetitions() {
      const competitionTableBody = document.getElementById('competition-table-body');
      competitionTableBody.innerHTML = '';

      const startIndex = (currentPage - 1) * itemsPerPage;
      const competitionsPage = competitionsList.slice(startIndex, startIndex + itemsPerPage);

      if (competitionsPage.length === 0) {
        const row = document.createElement('tr');
        const cell = document.createElement('td');
        cell.colSpan = 8;
        cell.textContent = 'No competitions available.';
        row.appendChild(cell);
        competitionTableBody.appendChild(row);
      } else {
        competitionsPage.forEach(function(competition) {
          // For tasks, show only the first two tasks and add "..." if there are more than 2
          let tasksArray = competition.tasks.map(task => task.name);
          let tasksDisplay = tasksArray.slice(0, 2).join(', ');
          if (tasksArray.length > 2) {
            tasksDisplay += '...';
          }
              
          // Truncate description and information to 10 characters with ellipsis
          const truncatedDescription = truncate(competition.description, 10);
          const truncatedInformation = truncate(competition.information, 10);

          const row = document.createElement('tr');
          // Make entire row clickable
          row.classList.add(
            'cursor-pointer',
            'hover:bg-gray-100',
            'dark:hover:bg-gray-700',
            'transition-colors',
            'duration-200'
          );
          row.onclick = function() {
            redirectToCompetition(competition);
          };
          row.innerHTML = `
            <td class="px-2 py-1 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
              ${competition.name}
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
              ${tasksDisplay}
            </td>
          `;
          competitionTableBody.appendChild(row);
        });
      }
      renderPaginationControls();
    }

    // Render pagination controls using arrow symbols
    function renderPaginationControls() {
      const paginationDiv = document.getElementById('pagination-controls');
      paginationDiv.innerHTML = '';

      const totalPages = Math.ceil(competitionsList.length / itemsPerPage);
      if (totalPages <= 1) return; // No pagination needed for one page

      // Previous Button with arrow
      const prevButton = document.createElement('button');
      prevButton.textContent = '←';
      prevButton.className = 'px-3 py-1 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded';
      prevButton.disabled = currentPage === 1;
      prevButton.addEventListener('click', () => {
        if (currentPage > 1) {
          currentPage--;
          renderCompetitions();
        }
      });
      paginationDiv.appendChild(prevButton);

      // Page Info
      const pageInfo = document.createElement('span');
      pageInfo.textContent = `${currentPage} of ${totalPages}`;
      paginationDiv.appendChild(pageInfo);

      // Next Button with arrow
      const nextButton = document.createElement('button');
      nextButton.textContent = '→';
      nextButton.className = 'px-3 py-1 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded';
      nextButton.disabled = currentPage === totalPages;
      nextButton.addEventListener('click', () => {
        if (currentPage < totalPages) {
          currentPage++;
          renderCompetitions();
        }
      });
      paginationDiv.appendChild(nextButton);
    }

    function loadCompetitions() {
      $.ajax({
        url: '/competitions/all',
        type: 'GET',
        dataType: 'json',
        success: function(competitions) {
          competitionsList = competitions;
          currentPage = 1; // Reset page to 1 on load
          renderCompetitions();
        },
        error: function() {
          console.error('Error loading competitions');
          const competitionTableBody = document.getElementById('competition-table-body');
          competitionTableBody.innerHTML = '';
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