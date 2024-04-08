<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Habits</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <?php include 'navbar.php'; ?>
  <div class="container mt-5">
    <h1 class="text-center mb-4">Habits Tracker</h1>
    <form action="habits.php" method="post" class="mb-4">
      <div class="form-group">
        <label for="habit">New Habit:</label>
        <input type="text" class="form-control" id="habit" name="habit" required>
      </div>
      <button type="submit" class="btn btn-primary">Add Habit</button>
    </form>
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover">
        <thead class="thead-dark">
          <tr>
            <th>Habit</th>
            <th>Days</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Your PHP code to fetch and display habits goes here
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script>
    // Function to update habit days based on system date
    function updateHabitDays() {
      var habits = document.querySelectorAll('.habit-row');
      habits.forEach(function(habit) {
        var startDate = new Date(habit.dataset.startDate);
        var currentDate = new Date();
        var diffTime = Math.abs(currentDate - startDate);
        var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        habit.querySelector('.days').textContent = diffDays;
      });
    }

    // Call the function initially
    updateHabitDays();

    // Check and update habit days every midnight
    setInterval(function() {
      var currentDate = new Date();
      if (currentDate.getHours() === 0 && currentDate.getMinutes() === 0) {
        updateHabitDays();
      }
    }, 60000); // Check every minute if it's midnight
    
    function restartHabit(habitId) {
      if (confirm("Are you sure you want to restart the habit?")) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "restarthabit.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
            if (xhr.responseText === 'success') {
              alert('Habit restarted successfully.');
              location.reload(); // Reload the page to update the habit list
            } else {
              alert('Failed to restart habit.');
            }
          }
        };
        xhr.send("habit_id=" + habitId);
      }
    }
  </script>
</body>

</html>