<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl sm:text-lg text-gray-800 dark:text-gray-200 leading-tight">
      All Competitions
    </h2>
  </x-slot>

  <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
    <div class="overflow-hidden p-2 sm:p-6">
      <!-- Action Button Container -->
      <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-4">
        <button
          id="routeButton"
          class="px-2 py-1 sm:px-4 sm:py-2 text-xs sm:text-sm bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 rounded shadow">
          Loading...
        </button>
        <input type="text" id="searchBar" placeholder="Search competitions..."  class="w-full sm:flex-1 p-1 sm:p-2 text-xs sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-gray-500">
      </div>
      
      <!-- Competitions Table -->
      <div class="overflow-x-auto">
        <table class="w-full min-w-full border-separate border-spacing-y-2">
          <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
              <!-- Name column: non-sortable -->
              <th class="px-2 py-1 sm:px-6 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-200 tracking-wider">
                Name
              </th>
              <!-- Sortable columns -->
              <th data-sort-field="time" class="sortable px-2 py-1 sm:px-6 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-200 tracking-wider">
                Time <span id="sortIndicator-time"></span>
              </th>
              <th data-sort-field="from" class="sortable px-2 py-1 sm:px-6 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-200 tracking-wider">
                From <span id="sortIndicator-from"></span>
              </th>
              <th data-sort-field="till" class="sortable px-2 py-1 sm:px-6 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-200 tracking-wider">
                Till <span id="sortIndicator-till"></span>
              </th>
              <th class="px-2 py-1 sm:px-6 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-200 tracking-wider">
                Description
              </th>
              <th class="px-2 py-1 sm:px-6 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-200 tracking-wider">
                Information
              </th>
              <!-- Difficulty column: non-sortable -->
              <th class="px-2 py-1 sm:px-6 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-200 tracking-wider">
                Difficulty
              </th>
              <!-- Tasks column: sortable by task count -->
              <th data-sort-field="tasks" class="sortable px-2 py-1 sm:px-6 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-200 tracking-wider">
                Tasks <span id="sortIndicator-tasks"></span>
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
    // Helper: Truncate text and add ellipsis if longer than maxLength
    function truncate(text, maxLength = 10) {
      if (text && text.length > maxLength) {
        return text.substring(0, maxLength) + '...';
      }
      return text;
    }

    let competitionsList = [];
    let filteredCompetitionsList = [];
    let currentPage = 1;
    const itemsPerPage = 10;

    // Global sorting variables. An empty sortField means no sorting.
    let sortField = '';
    let sortOrder = 'asc'; // 'asc' is default when sorting is applied

    document.addEventListener('DOMContentLoaded', function() {
      updateButton();
      loadCompetitions();

      // Filter competitions as user types
      document.getElementById('searchBar').addEventListener('input', filterCompetitions);

      // Attach click events to sortable headers (only those with data-sort-field)
      document.querySelectorAll('.sortable').forEach(header => {
        header.addEventListener('click', function() {
          const field = this.getAttribute('data-sort-field');
          if (sortField === field) {
            // Cycle: asc -> desc -> no sorting
            if (sortOrder === 'asc') {
              sortOrder = 'desc';
            } else if (sortOrder === 'desc') {
              sortField = ''; // Remove sorting
              sortOrder = 'asc'; // Reset order for next time
            }
          } else {
            sortField = field;
            sortOrder = 'asc';
          }
          updateSortIndicators();
          renderCompetitions();
        });
      });
    });

    function updateButton() {
      fetch('/get-class')
        .then(response => response.json())
        .then(data => {
          const button = document.getElementById('routeButton');
          if (!button) return;
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
        })
        .catch(error => {
          console.error('Error fetching class:', error);
          const button = document.getElementById('routeButton');
          if (button) {
            button.textContent = 'Error loading class!';
          }
        });
    }

    function filterCompetitions() {
      const query = document.getElementById('searchBar').value.toLowerCase();
      filteredCompetitionsList = competitionsList.filter(function(competition) {
        return competition.name.toLowerCase().includes(query) ||
               competition.description.toLowerCase().includes(query) ||
               competition.information.toLowerCase().includes(query);
      });
      currentPage = 1; // Reset to first page after filtering
      renderCompetitions();
    }

    // Helper: Compare values for sorting
    function compareValues(a, b, order = 'asc') {
      if (a < b) return order === 'asc' ? -1 : 1;
      if (a > b) return order === 'asc' ? 1 : -1;
      return 0;
    }

    // Render competitions with filtering, sorting, and pagination applied
    function renderCompetitions() {
      const competitionTableBody = document.getElementById('competition-table-body');
      competitionTableBody.innerHTML = '';

      // Use filtered list if available, otherwise full list
      let listToRender = filteredCompetitionsList.length ? filteredCompetitionsList.slice() : competitionsList.slice();

      // If sorting is applied, sort the list
      if (sortField) {
        if (sortField === 'tasks') {
          // Sort by the number of tasks
          listToRender.sort((a, b) =>
            compareValues(a.tasks.length, b.tasks.length, sortOrder)
          );
        } else {
          listToRender.sort((a, b) =>
            compareValues(a[sortField], b[sortField], sortOrder)
          );
        }
      }

      // Pagination
      const startIndex = (currentPage - 1) * itemsPerPage;
      const competitionsPage = listToRender.slice(startIndex, startIndex + itemsPerPage);

      if (competitionsPage.length === 0) {
        const row = document.createElement('tr');
        const cell = document.createElement('td');
        cell.colSpan = 8;
        cell.textContent = 'No competitions available.';
        row.appendChild(cell);
        competitionTableBody.appendChild(row);
      } else {
        competitionsPage.forEach(function(competition) {
          // For tasks: display first two task names; add ellipsis if more exist
          let tasksArray = competition.tasks.map(task => task.name);
          let tasksDisplay = tasksArray.slice(0, 2).join(', ');
          if (tasksArray.length > 2) {
            tasksDisplay += '...';
          }

          // Truncate description and information to 10 characters with ellipsis
          const truncatedDescription = truncate(competition.description, 10);
          const truncatedInformation = truncate(competition.information, 10);

          const row = document.createElement('tr');
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
            <td class="px-2 py-1 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">${competition.name}</td>
            <td class="px-2 py-1 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">${competition.time}</td>
            <td class="px-2 py-1 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">${competition.from}</td>
            <td class="px-2 py-1 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">${competition.till}</td>
            <td class="px-2 py-1 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">${truncatedDescription}</td>
            <td class="px-2 py-1 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">${truncatedInformation}</td>
            <td class="px-2 py-1 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">${competition.difficulty}</td>
            <td class="px-2 py-1 sm:px-6 sm:py-4 whitespace-nowrap text-xs sm:text-sm">${tasksDisplay}</td>
          `;
          competitionTableBody.appendChild(row);
        });
      }
      renderPaginationControls(listToRender.length);
    }

    // Update sort indicator arrows on headers
    function updateSortIndicators() {
      // Clear all indicators
      document.querySelectorAll('.sortable span').forEach(span => {
        span.innerHTML = '';
      });
      if (sortField) {
        let indicator = document.getElementById('sortIndicator-' + sortField);
        indicator.innerHTML = sortOrder === 'asc' ? '&uarr;' : '&darr;';
      }
    }

    function renderPaginationControls(totalItems) {
      const paginationDiv = document.getElementById('pagination-controls');
      paginationDiv.innerHTML = '';

      const totalPages = Math.ceil(totalItems / itemsPerPage);
      if (totalPages <= 1) return; // No pagination needed

      // Previous button
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

      // Page info
      const pageInfo = document.createElement('span');
      pageInfo.textContent = `${currentPage} of ${totalPages}`;
      paginationDiv.appendChild(pageInfo);

      // Next button
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
          filteredCompetitionsList = competitionsList.slice();
          currentPage = 1;
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