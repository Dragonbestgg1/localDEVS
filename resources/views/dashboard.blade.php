<x-app-layout>
    <x-slot name="header">
        <h2 class="leading-tight">
            {{ __('Jaunumi') }}
        </h2>
    </x-slot>

    <div class="relative">
        <!-- Notification -->
        <div id="loginNotification" class="p-4 bg-gray-200 dark:bg-gray-500 text-gray-800 dark:text-white rounded fixed top-4 right-4 shadow-lg transition-opacity duration-1000 z-10">
            {{ __("You're logged in!") }}
        </div>
        
        <!-- News List -->
        <div id="newsList" class="max-w-7xl">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- News items will be appended here -->
            </div>
        </div>

        <!-- Floating Form (hidden by default) -->
        <div id="floatingForm" class="fixed bottom-24 right-4 sm:right-12 hidden w-full sm:max-w-md bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg z-10">
            <button id="formCloseButton" class="absolute top-2 right-2 text-gray-700 dark:text-gray-300 text-2xl focus:outline-none">&times;</button>
            <form id="newsForm" method="POST" action="/news/store">
                @csrf
                <label for="title" class="block mb-1">Title:</label>
                <input type="text" id="title" name="title" required
                    class="w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-gray-500">
                <br><br>
                <label for="description" class="block mt-4 mb-1">Description:</label>
                <textarea id="description" name="description" required
                    class="w-full resize-none p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-gray-500 h-32"></textarea>
                <br><br>
                <input type="hidden" id="author" name="author">
                <button type="submit"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Submit
                </button>
            </form>
        </div>

        <!-- Floating Action Button (FAB) -->
        <button id="fabAddButton" class="fixed bottom-7 right-4 sm:right-12 p-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded-full shadow-lg flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-gray-500 z-10">
            <span class="text-base">Pievienot Jaunumus</span>
        </button>
    </div>

    <script>
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

            // Toggle floating form display when FAB is clicked
            const fab = document.getElementById('fabAddButton');
            const floatingForm = document.getElementById('floatingForm');
            const formCloseButton = document.getElementById('formCloseButton');

            // Enable FAB only for teachers
            fetch('/get-class')
                .then(response => response.json())
                .then(data => {
                    if (data.class === 'teacher') {
                        fab.style.display = 'flex';
                        fab.addEventListener('click', function() {
                            if (floatingForm.classList.contains('hidden')) {
                                floatingForm.classList.remove('hidden');
                                fab.innerHTML = '<span class="text-base">&times;</span>';
                            } else {
                                floatingForm.classList.add('hidden');
                                fab.innerHTML = '<span class="text-base">Pievienot Jaunumus</span>';
                            }
                        });
                        formCloseButton.addEventListener('click', function() {
                            floatingForm.classList.add('hidden');
                            fab.innerHTML = '<span class="text-base">Pievienot Jaunumus</span>';
                        });
                    } else {
                        fab.style.display = 'none';
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
                        floatingForm.classList.add('hidden');
                        fab.innerHTML = '<span class="text-base">Pievienot Jaunumus</span>';
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to add news item');
                    });
            });

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

            fetch('/export-classes')
                .then(response => response.json())
                .then(data => {
                    console.log('Export classes result:', data);
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
</x-app-layout>