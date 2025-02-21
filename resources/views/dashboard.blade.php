<x-app-layout>
  <x-slot name="header">
    <h2 class="leading-tight">
      {{ __('Jaunumi') }}
    </h2>
  </x-slot>

  <div class="relative">
    <!-- Notification -->
    <div id="loginNotification" 
         class="p-4 bg-gray-200 dark:bg-gray-500 text-gray-800 dark:text-white 
                rounded fixed top-4 right-4 shadow-lg 
                transition-opacity duration-1000 z-10">
      {{ __("You're logged in!") }}
    </div>

    <!-- Search and Sort Container -->
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 mb-4">
      <div class="flex flex-col sm:flex-row sm:items-center gap-2">
        <input
          id="newsSearch"
          type="text"
          placeholder="Search news..."
          class="w-full sm:flex-1 p-1 sm:p-2 text-xs sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-gray-500">
        <button id="sortDateButton"
                class="px-2 py-2 text-xs sm:text-sm bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 rounded">
          Sort by Date <span id="sortDateIndicator"></span>
        </button>
      </div>
    </div>

    <!-- News List (centered on wide screens) -->
    <div id="newsList" class="max-w-7xl mx-auto flex justify-center">
      <div id="newsContainer" class="p-6 w-full max-w-screen-xl text-gray-900 dark:text-gray-100">
        <!-- News items will be rendered here -->
      </div>
    </div>

    <!-- News Pagination Controls -->
    <div id="news-pagination-controls" class="mt-4 flex justify-center items-center space-x-4"></div>

    <!-- Floating Form (hidden by default) -->
    <div id="floatingForm" 
         class="fixed bottom-24 right-4 border-2 border-gray-200 dark:border-gray-500 
                sm:right-12 hidden w-full sm:max-w-md bg-white dark:bg-gray-800 
                p-6 rounded-lg shadow-lg z-10">
      <!-- Added close button -->
      <button id="formCloseButton" 
              class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">
        &times;
      </button>
      <form id="newsForm" method="POST" action="/news/store">
        @csrf
        <label for="title" class="block mb-1">Title:</label>
        <input type="text" id="title" name="title" required
               class="w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                      border border-gray-300 dark:border-gray-600 
                      rounded focus:outline-none focus:ring-2 focus:ring-gray-500">
        <br><br>

        <label for="description" class="block mt-4 mb-1">Description:</label>
        <textarea id="description" name="description" required
                  class="w-full resize-none p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                         border border-gray-300 dark:border-gray-600 
                         rounded focus:outline-none focus:ring-2 focus:ring-gray-500 h-32"></textarea>
        <br><br>

        <input type="hidden" id="author" name="author">

        <button type="submit"
                class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-100 
                       rounded focus:outline-none focus:ring-2 focus:ring-gray-500">
          Submit
        </button>
      </form>
    </div>

    <!-- Floating Action Button (FAB) -->
    <button id="fabAddButton" 
            class="fixed bottom-8 right-4 sm:right-12 
                   w-10 h-10 rounded-full 
                   bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-100 
                   shadow-lg flex items-center justify-center 
                   focus:outline-none focus:ring-2 focus:ring-gray-400 z-10">
      <!-- Default icon: plus sign -->
      <span class="text-2xl">+</span>
    </button>
  </div>

  <script>
    // Global variables for news pagination, filtering, and sorting
    let newsData = [];
    let newsCurrentPage = 1;
    const newsItemsPerPage = 10;
    let newsSearchQuery = "";
    let newsSortOrder = ""; // "", "asc", or "desc"

    document.addEventListener('DOMContentLoaded', function() {
      // Hide login notification after 5 seconds with fade-out effect
      const loginNotification = document.getElementById('loginNotification');
      if (loginNotification) {
        setTimeout(() => {
          loginNotification.classList.add('opacity-0');
          setTimeout(() => {
            loginNotification.remove();
          }, 1000);
        }, 5000);
      }

      // Floating form and FAB elements
      const fab = document.getElementById('fabAddButton');
      const floatingForm = document.getElementById('floatingForm');
      const formCloseButton = document.getElementById('formCloseButton');

      // Enable the FAB only for teachers
      fetch('/get-class')
        .then(response => response.json())
        .then(data => {
          if (data.class === 'teacher') {
            fab.style.display = 'flex';
          } else {
            fab.style.display = 'none';
          }
        })
        .catch(error => console.error('Error:', error));

      // Toggle the floating form on FAB click
      fab.addEventListener('click', function() {
        if (floatingForm.classList.contains('hidden')) {
          floatingForm.classList.remove('hidden');
          fab.innerHTML = '<span class="text-2xl">&times;</span>';
        } else {
          floatingForm.classList.add('hidden');
          fab.innerHTML = '<span class="text-2xl">+</span>';
        }
      });

      // Close button inside the form
      formCloseButton.addEventListener('click', function() {
        floatingForm.classList.add('hidden');
        fab.innerHTML = '<span class="text-2xl">+</span>';
      });

      // Fetch decrypted author name and surname from the server
      fetch('/get-author')
        .then(response => response.json())
        .then(data => {
          document.getElementById('author').value = `${data.name} ${data.surname}`;
        })
        .catch(error => console.error('Error:', error));

      // AJAX form submission for news
      document.getElementById('newsForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        fetch('/news/store', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
          },
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          alert('News item added successfully');
          document.getElementById('newsForm').reset();
          floatingForm.classList.add('hidden');
          fab.innerHTML = '<span class="text-2xl">+</span>';
          location.reload();
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Failed to add news item');
        });
      });

      // Event listener for the news search bar
      document.getElementById('newsSearch').addEventListener('input', function() {
        newsSearchQuery = this.value.toLowerCase().trim();
        newsCurrentPage = 1; // Reset pagination on search
        renderNews();
      });

      // Event listener for the "Sort by Date" button
      document.getElementById('sortDateButton').addEventListener('click', function() {
        // Cycle through: no sort -> descending -> ascending -> no sort
        if (newsSortOrder === "") {
          newsSortOrder = "desc";
        } else if (newsSortOrder === "desc") {
          newsSortOrder = "asc";
        } else {
          newsSortOrder = "";
        }
        updateSortDateIndicator();
        renderNews();
      });

      // Fetch all news items on page load
      fetch('/news')
        .then(response => response.json())
        .then(data => {
          newsData = data;
          newsCurrentPage = 1;
          renderNews();
        })
        .catch(error => console.error('Error fetching news:', error));

      // Example fetch for /export-classes
      fetch('/export-classes')
        .then(response => response.json())
        .then(data => {
          console.log('Export classes result:', data);
        })
        .catch(error => console.error('Error:', error));
    });

    // Update the sort indicator for the date button
    function updateSortDateIndicator() {
      const indicator = document.getElementById('sortDateIndicator');
      if (newsSortOrder === "asc") {
        indicator.innerHTML = '&uarr;';
      } else if (newsSortOrder === "desc") {
        indicator.innerHTML = '&darr;';
      } else {
        indicator.innerHTML = '';
      }
    }

    // Render news items applying search filter, date sorting, and pagination
    function renderNews() {
      const newsContainer = document.getElementById('newsContainer');
      newsContainer.innerHTML = '';

      // Apply search filter
      let filteredNews = newsData.filter(news => {
        return news.title.toLowerCase().includes(newsSearchQuery) ||
               news.description.toLowerCase().includes(newsSearchQuery);
      });

      // Apply date sorting if set
      if (newsSortOrder !== "") {
        filteredNews.sort((a, b) => {
          const dateA = new Date(a.updated_at);
          const dateB = new Date(b.updated_at);
          return newsSortOrder === "asc" ? dateA - dateB : dateB - dateA;
        });
      }

      // Pagination
      const startIndex = (newsCurrentPage - 1) * newsItemsPerPage;
      const newsPage = filteredNews.slice(startIndex, startIndex + newsItemsPerPage);

      if (newsPage.length === 0) {
        newsContainer.textContent = 'No news available.';
      } else {
        newsPage.forEach(news => {
          const newsItem = document.createElement('div');
          newsItem.className = 'bg-gray-100 dark:bg-gray-900 p-4 my-4 w-full rounded shadow-md';
          newsItem.innerHTML = `
            <h3 class="font-bold text-lg">${news.title}</h3>
            <p>${news.description}</p>
            <small>Autors: ${news.author}</small><br>
            <small>Publicēts: ${new Date(news.updated_at).toLocaleDateString()}</small>
          `;
          newsContainer.appendChild(newsItem);
        });
      }
      renderNewsPaginationControls(filteredNews.length);
    }

    // Render pagination controls for news using arrow symbols
    function renderNewsPaginationControls(totalItems) {
      const paginationDiv = document.getElementById('news-pagination-controls');
      paginationDiv.innerHTML = '';

      const totalPages = Math.ceil(totalItems / newsItemsPerPage);
      if (totalPages <= 1) return; // No pagination needed

      // Previous arrow button
      const prevButton = document.createElement('button');
      prevButton.textContent = '←';
      prevButton.className = 'px-3 py-1 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded';
      prevButton.disabled = (newsCurrentPage === 1);
      prevButton.addEventListener('click', () => {
        if (newsCurrentPage > 1) {
          newsCurrentPage--;
          renderNews();
        }
      });
      paginationDiv.appendChild(prevButton);

      // Page info
      const pageInfo = document.createElement('span');
      pageInfo.textContent = `Page ${newsCurrentPage} of ${totalPages}`;
      paginationDiv.appendChild(pageInfo);

      // Next arrow button
      const nextButton = document.createElement('button');
      nextButton.textContent = '→';
      nextButton.className = 'px-3 py-1 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded';
      nextButton.disabled = (newsCurrentPage === totalPages);
      nextButton.addEventListener('click', () => {
        if (newsCurrentPage < totalPages) {
          newsCurrentPage++;
          renderNews();
        }
      });
      paginationDiv.appendChild(nextButton);
    }
  </script>
</x-app-layout>