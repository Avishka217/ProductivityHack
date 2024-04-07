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
          // Connect to database
          $conn = mysqli_connect("localhost", "root", "root", "wp_db");
          if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
          }

          // Handle form submission
          if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $habit = $_POST['habit'];
            $sql = "INSERT INTO habits (habit, days) VALUES ('$habit', 1)";
            if (mysqli_query($conn, $sql)) {
              echo "<meta http-equiv='refresh' content='0'>"; // Refresh page to update list
            } else {
              echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
          }

          // Display habits from database
          $sql = "SELECT * FROM habits";
          $result = mysqli_query($conn, $sql);
          if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>";
              echo "<td>" . $row['habit'] . "</td>";
              echo "<td>" . $row['days'] . "</td>";
              echo "<td><button class='btn btn-danger' onclick='restartHabit(" . $row['id'] . ")'>Restart</button></td>";
              echo "</tr>";
            }
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