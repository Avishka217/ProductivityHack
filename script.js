// Initialize points variable
let points = 0;

// Function to update points display
function updatePoints() {
  document.getElementById("points").innerText = points;
}

// Increase points function
function increasePoints() {
  points++;
  updatePoints();
}

// Decrease points function
function decreasePoints() {
    points--;
    updatePoints();
  
}

// Function to update hidden input value before form submission
function updatePointsInput() {
  document.getElementById("pointsInput").value = points;
}

// Event listeners for buttons
document
  .getElementById("increaseBtn")
  .addEventListener("click", increasePoints);
document
  .getElementById("decreaseBtn")
  .addEventListener("click", decreasePoints);
document
  .getElementById("pointsForm")
  .addEventListener("submit", updatePointsInput);


// Function to fetch insights from the server
function fetchInsights() {
    fetch('fetch_insights.php')
    .then(response => response.json())
    .then(data => {
        // Clear existing rows
        document.getElementById('insightsTableBody').innerHTML = '';

        // Add new rows
        data.forEach(insight => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${insight.date}</td>
                <td>${insight.points}</td>
            `;
            document.getElementById('insightsTableBody').appendChild(row);
        });
    })
    .catch(error => console.error('Error fetching insights:', error));
}

// Call fetchInsights function on page load
fetchInsights();

// Get URL parameters
const urlParams = new URLSearchParams(window.location.search);
const message = urlParams.get('message');

// Display message if available
if (message) {
    alert(message);
}