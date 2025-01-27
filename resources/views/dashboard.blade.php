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
    </div>

    <script>
        // Fetch the class value from the server
        fetch('/get-class')
            .then(response => response.json())
            .then(data => {
                console.log('Class cookie value:', data.class);

                if (data.class === 'teacher') {
                    console.log('Teacher class detected. Displaying the Add button.');
                    // Create and append the Add button
                    var addButton = document.createElement('button');
                    addButton.className = 'btn btn-secondary';
                    addButton.innerText = 'Add';
                    document.getElementById('addButtonPlaceholder').appendChild(addButton);
                } else {
                    console.log('Teacher class not detected.');
                }
            })
            .catch(error => console.error('Error:', error));
    </script>
</x-app-layout>
