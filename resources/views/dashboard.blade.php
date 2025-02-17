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
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" required><br><br>

                        <label for="description">Description:</label>
                        <textarea id="description" name="description" required></textarea><br><br>

                        <input type="hidden" id="author" name="author">

                        <button type="submit">Submit</button>
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
                    console.log('Class cookie value:', data.class);

                    if (data.class === 'teacher') {
                        console.log('Teacher class detected. Displaying the Add button.');
                        // Create and append the Add button with toggle functionality
                        var addButton = document.createElement('button');
                        addButton.className = 'btn btn-secondary';
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
                    } else {
                        console.log('Teacher class not detected.');
                    }
                })
                .catch(error => console.error('Error:', error));

            // Fetch decrypted author name and surname from the server
            fetch('/get-author')
                .then(response => response.json())
                .then(data => {
                    console.log('Decrypted name:', data.name);
                    console.log('Decrypted surname:', data.surname);

                    // Set the author hidden input value
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
                        console.log('Success:', data);
                        alert('News item added successfully');
                        document.getElementById('newsForm').reset();
                        document.getElementById('newsForm').style.display = 'none';
                        // Optionally, update the button text back to "Add" if needed:
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