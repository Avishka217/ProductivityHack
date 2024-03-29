<?php
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

// Process form data only if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['activity']) && isset($_POST['milestone'])) {
  // Get current date
  $date = date('Y-m-d');

  // Get form data (assuming proper sanitization is done)
  $activity = $_POST["activity"];
  $milestone = $_POST["milestone"];

  // SQL insert statement
  $sql = "INSERT INTO milestones (date, activity, milestone)
          VALUES ('$date', '$activity', '$milestone')";

  if ($conn->query($sql) === TRUE) {
    // Redirect to avoid form resubmission on page refresh
    header("Location: {$_SERVER['REQUEST_URI']}");
    exit();
  } else {
    echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
  }
}

// Retrieve data for today's milestones
$date = date('Y-m-d');
$sql = "SELECT activity , milestone FROM milestones WHERE date = '$date'";
$result = $conn->query($sql);

$tableBody = ""; // Initialize empty string for table body content

if ($result->num_rows > 0) {
  // Output data of each row
  while ($row = $result->fetch_assoc()) {
    $tableBody .= "<tr>";

    $tableBody .= "<td>" . $row["activity"] . "</td>";
    $tableBody .= "<td>" . $row["milestone"] . "</td>";
    $tableBody .= "</tr>";
  }
} else {
  $tableBody = "<tr><td colspan='3'>No milestones found for today.</td></tr>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Points Tracker</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      /* Set a standard web font */
      margin: 0;
      padding: 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    th,
    td {
      padding: 8px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #f2f2f2;
    }

    tbody tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    form {
      margin-bottom: 20px;
    }

    .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 5px;
      background-color: #fff;
    }

    h1,
    h2 {
      margin-top: 0;
    }

    button {
      padding: 10px 20px;
      font-size: 16px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      background-color: #007bff;
      color: #fff;
      margin: 0.5rem;
    }

    button:hover {
      background-color: #0056b3;
    }

    button,
    input[type="submit"] {
      padding: 10px 20px;
      font-size: 16px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      background-color: #007bff;
      color: #fff;
    }

    button:hover,
    input[type="submit"]:hover {
      background-color: #0056b3;
    }

    input[type="text"],
    input[type="submit"] {
      padding: 10px;
      font-size: 16px;
      border: 1px solid #ddd;
      border-radius: 5px;
      margin-bottom: 10px;
    }

    #increaseBtn {
      background-color: #4CAF50;
      /* Green */
      color: white;
    }

    #decreaseBtn {
      background-color: #f44336;
      /* Light Red */
      color: white;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Points Tracker</h1>
    <div id="pointsDisplay">
      Points: <span id="points">0</span>
    </div>
    <button id="increaseBtn">Increase Points</button>
    <button id="decreaseBtn">Decrease Points</button>
    <form id="pointsForm" action="submit.php" method="post">
      <input type="hidden" id="pointsInput" name="points">
      <button type="submit" id="submitBtn">Submit Points</button>
    </form>

    <h2>Insights</h2>
    <table id="insightsTable">
      <thead>
        <tr>
          <th>Date</th>
          <th>Points</th>
        </tr>
      </thead>
      <tbody id="insightsTableBody">
        <!-- Table rows will be inserted here dynamically -->
      </tbody>
    </table>

    <h1>Add Milestone</h1>
    <form action="" method="post">
      <label for="activity">Activity:</label>
      <input type="text" name="activity" id="activity" required>
      <br>
      <label for="milestone">Milestone:</label>
      <input type="text" name="milestone" id="milestone" required>
      <br>
      <button type="submit" id="submitBtn">Add Milestone</button>
    </form>

    <h2>Today's Milestones</h2>
    <table>
      <thead>
        <tr>

          <th>Activity</th>
          <th>Milestone</th>
        </tr>
      </thead>
      <tbody id="milestones-table">
        <?php echo $tableBody; ?>
      </tbody>
    </table>

    <!-- 
    <div class="div">
      <p>Avoid Distractions as much as possible</p>
      <p>Do not get curious about things that are not relevant </p>
      <p>Allocate free time to teach ICT </p>
    </div> -->

  </div>

  <script src="script.js"></script>
</body>

</html>