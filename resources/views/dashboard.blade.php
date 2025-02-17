<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Jaunumi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>

        <!-- Add Button placeholder -->
        <div id="addButtonPlaceholder" class="flex items-center justify-center mt-4"></div>

        <!-- Add News Form -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form id="newsForm" method="POST" action="/news/store">
                        @csrf
                        <label for="title" class="block mb-1">Title:</label>
                        <input type="text" id="title" name="title" required
                            class="w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <br><br>

                        <label for="description" class="block mt-4 mb-1">Description:</label>
                        <textarea id="description" name="description" required
                            class="w-full p-2 bg-gray-100 dark:bg-gray-700 justify-content:centertext-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-gray-500"></textarea>
                        <br><br>

                        <input type="hidden" id="author" name="author">

                        <button type="submit"
                            class="mt-4 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:bg-gray-600 dark:hover:bg-gray-500 dark:focus:ring-gray-400">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- News List -->
        <div id="newsList" class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- News items will be appended here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch all news items on page load
            fetch('/news')
                .then(response => response.json())
                .then(data => {
                    const newsList = document.getElementById('newsList').querySelector('.p-6');
                    data.forEach(news => {
                        const newsItem = document.createElement('div');
                        newsItem.className = 'bg-gray-100 dark:bg-gray-900 p-4 my-4 rounded shadow-md';
                        newsItem.innerHTML = `
                            <h3 class="font-bold text-lg">${news.title}</h3>
                            <p>${news.description}</p>
                            <small>Autors: ${news.author}</small><br>
                            <small>Publicēts: ${new Date(news.updated_at).toLocaleDateString()}</small>
                        `;
                        newsList.appendChild(newsItem);
                    });
                })
                .catch(error => console.error('Error fetching news:', error));

            fetch('/get-class')
                .then(response => response.json())
                .then(data => {
                    if (data.class === 'teacher') {
                        // Create and append the Add button with toggle functionality
                        var addButton = document.createElement('button');
                        addButton.className = 'px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded focus:outline-none focus:ring-2 focus:ring-blue-500';
                        addButton.innerText = 'Add';
                        addButton.onclick = function() {
                            var newsForm = document.getElementById('newsForm');
                            if (newsForm.style.display === 'none' || newsForm.style.display === '') {
                                newsForm.style.display = 'block';
                                addButton.innerText = 'Close';
                            } else {
                                newsForm.style.display = 'none';
                                addButton.innerText = 'Add';
                            }
                        };
                        document.getElementById('addButtonPlaceholder').appendChild(addButton);
                    }
                })
                .catch(error => console.error('Error:', error));

            // Fetch decrypted author name and surname from the server
            fetch('/get-author')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('author').value = `${data.name} ${data.surname}`;
                })
                .catch(error => console.error('Error:', error));

            // Hide the form initially
            document.getElementById('newsForm').style.display = 'none';

            // AJAX form submission
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
                        document.getElementById('newsForm').style.display = 'none';
                        var addButton = document.getElementById('addButtonPlaceholder').querySelector('button');
                        if (addButton) addButton.innerText = 'Add';
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to add news item');
                    });
            });

            fetch('/export-classes')
                .then(response => response.json())
                .then(data => {
                    console.log('Export classes result:', data);
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
</x-app-layout>