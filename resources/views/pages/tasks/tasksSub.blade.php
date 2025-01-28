<x-app-layout>
    <button id="backButton">Atpakaļ</button>

    <script>
        async function updateButton() {
            try {
                const response = await fetch('/get-class');
                const data = await response.json();

            } catch (error) {
                console.error('Error fetching class:', error);
                button.textContent = 'Error loading class!';
            }
        }

        document.getElementById('backButton').addEventListener('click', () => {
            window.location.href = '/tasks';
        });

        updateButton();
    </script>
</x-app-layout>
