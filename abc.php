<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Milestone Tracker</title>
</head>

<body>
  <h1>Add Milestone</h1>
  <form action="" method="post"> <label for="activity">Activity:</label>
    <input type="text" name="activity" id="activity" required>
    <br>
    <label for="milestone">Milestone:</label>
    <input type="text" name="milestone" id="milestone" required>
    <br>
    <input type="submit" value="Add">
  </form>

  <h2>Today's Milestones</h2>
  <table>
    <thead>
      <tr>
        <th>Date</th>
        <th>Activity</th>
        <th>Milestone</th>
      </tr>
    </thead>
    <tbody id="milestones-table"> </tbody>
  </table>

  <?php // Moved PHP code inside the page 
  // Database connection details (replace with your actual details)
  $servername = "localhost";
  $username = "root";
  $password = "root";
  $dbname = "wp_db";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Process form data only if submitted (using $_SERVER['REQUEST_METHOD'])
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get current date
    $date = date('Y-m-d');

    // Get form data (assuming proper sanitization is done)
    $activity = $_POST["activity"];
    $milestone = $_POST["milestone"];

    // SQL insert statement
    $sql = "INSERT INTO milestones (date, activity, milestone)
            VALUES ('$date', '$activity', '$milestone')";

    if ($conn->query($sql) === TRUE) {
      echo "<p>New record created successfully!</p>";
    } else {
      echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }

    // Retrieve data for today's milestones
    $sql = "SELECT * FROM milestones WHERE date = '$date'";
    $result = $conn->query($sql);

    $tableBody = ""; // Initialize empty string for table body content

    if ($result->num_rows > 0) {
      // Output data of each row
      while ($row = $result->fetch_assoc()) {
        $tableBody .= "<tr>";
        $tableBody .= "<td>" . $row["date"] . "</td>";
        $tableBody .= "<td>" . $row["activity"] . "</td>";
        $tableBody .= "<td>" . $row["milestone"] . "</td>";
        $tableBody .= "</tr>";
      }
    } else {
      $tableBody = "<tr><td colspan='3'>No milestones found for today.</td></tr>";
    }

    // Update table body content using JavaScript
    echo "<script>document.getElementById('milestones-table').innerHTML = '$tableBody';</script>";
  }

  $conn->close();
  ?>
</body>

</html>