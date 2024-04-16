<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Habits</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      /* Set a standard web font */
      margin: 0;
      padding: 0;
    }


    .container {
      max-width: 800px;
      margin: 20px auto;
      padding: 20px;
      background-color: #F1EEDC;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>

<body>
  <?php include 'navbar.php'; ?>
  <div class="container">
    <h1 class="text-center mb-4">Habits Tracker</h1>
    <form action="habits.php" method="post" class="mb-4">
      <div class="form-group">
        <label for="habit">New Habit:</label>
        <input type="text" class="form-control" id="habit" name="habit" required>
      </div>
      <button type="submit" class="btn btn-primary" name="submit">Add Habit</button>
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
          // Connect to database
          $conn = mysqli_connect("localhost", "root", "root", "wp_db");
          if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
          }

          // Handle form submission
          if (isset($_POST['submit'])) {
            $habit = $_POST['habit'];
            $sql = "INSERT INTO habits (habit) VALUES ('$habit')";
            if (mysqli_query($conn, $sql)) {
              // Success message
              echo "<script>alert('Habit added successfully.');</script>";
              // Refresh the page to display the updated habit list
              echo "<meta http-equiv='refresh' content='0'>";
            } else {
              // Error message
              echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
          }

          // Fetch habits from database
          $sql = "SELECT * FROM habits";
          $result = mysqli_query($conn, $sql);
          if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              // Echo out HTML for each habit row
              echo "<tr class='habit-row' data-start-date='" . $row['start_date'] . "'>";
              echo "<td>" . $row['habit'] . "</td>";
              echo "<td class='days'></td>";
              echo "<td><button class='btn btn-danger' onclick='restartHabit(" . $row['id'] . ")'>Restart</button></td>";
              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='3'>No habits found.</td></tr>";
          }
          mysqli_close($conn);
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
              // Reload the page to get the updated habit list
              window.location.reload();
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