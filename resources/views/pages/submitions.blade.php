<x-app-layout>
    <div class="container">
        <!-- Button for filter actions -->
        <button onclick="openFilterDropdown()">Filter Submissions</button>
        <div id="filterDropdown" class="dropdown-content" style="display: none;">
            <!-- These options will be dynamically shown based on the user's class -->
            <div id="studentOptions">
                <button onclick="filterByClass()">Filter by Class (Default)</button>
                <button id="mySubmissionsButton" onclick="filterStudentSubmissions()">Show My Submissions</button>
            </div>
            <div id="teacherOptions">
                <button onclick="filterByClass()">Filter by Class (Default)</button>
                <button onclick="filterSchoolSubmissions()">Show School Submissions</button>
            </div>
        </div>
        <button onclick="refetchData()">Refetch Data</button>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Determine the user class from the server and show relevant options
        getUserClassFromServer()
            .then(userClass => {
                console.log("User class:", userClass); // Debug statement

                if (userClass === 'student') {
                    document.getElementById('studentOptions').style.display = 'block';
                    document.getElementById('teacherOptions').style.display = 'none';
                } else if (userClass === 'teacher') {
                    document.getElementById('studentOptions').style.display = 'none';
                    document.getElementById('teacherOptions').style.display = 'block';
                    document.getElementById('mySubmissionsButton').style.display = 'none';
                    filterSchoolSubmissions();
                }
            })
            .catch(error => {
                console.error("Error fetching user class:", error);
                alert("Failed to fetch user class.");
            });
    });

    function openFilterDropdown() {
        var dropdown = document.getElementById("filterDropdown");
        dropdown.style.display = dropdown.style.display === "none" ? "block" : "none";
    }

    // Functions for the filter actions
    function filterByClass() {
        fetch('/get-class')
            .then(response => response.json())
            .then(data => {
                if (data.class) {
                    alert("Filtering by class: " + data.class);
                    // Add your filtering logic here
                } else {
                    alert("No class found in the cookie.");
                }
            })
            .catch(error => {
                console.error("Error fetching class:", error);
                alert("Failed to fetch class.");
            });
    }

    function filterStudentSubmissions() {
        fetch('/student/submissions')
            .then(response => response.json())
            .then(data => {
                alert("Filtering to show only your submissions...");
                // Update the UI with the student's submissions
                console.log(data);
            })
            .catch(error => {
                console.error("Error fetching student submissions:", error);
                alert("Failed to fetch student submissions.");
            });
    }

    function filterSchoolSubmissions() {
        alert("Filtering to show all school submissions...");
        // Add your filtering logic here
    }

    function refetchData() {
        alert("Refetching data...");
        // Add your data fetching logic here
    }

    // Function to get user class from server
    function getUserClassFromServer() {
        return fetch('/get-class')
            .then(response => response.json())
            .then(data => {
                if (data.class) {
                    return data.class;
                } else {
                    throw new Error("No class found in the response.");
                }
            });
    }
</script>

<style>
    .container {
        display: flex;
        justify-content: space-around;
        margin-top: 20px;
        position: relative;
    }

    button {
        padding: 10px 20px;
        font-size: 16px;
    }

    .dropdown-content {
        position: absolute;
        top: 40px;
        left: 10px;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
        display: none;
        /* Hide by default */
    }

    .dropdown-content button {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }
</style>
