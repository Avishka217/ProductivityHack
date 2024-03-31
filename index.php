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
  $sql = "INSERT INTO milestones (date, activity, milestone, completion_state)
          VALUES ('$date', '$activity', '$milestone', 0)";

  if ($conn->query($sql) === TRUE) {
    // Redirect to avoid form resubmission on page refresh
    header("Location: {$_SERVER['REQUEST_URI']}");
    exit();
  } else {
    echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
  }
}

// Update completion state if the Done button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['milestone_id'])) {
  $milestone_id = $_POST['milestone_id'];

  // SQL update statement to set completion state to 1
  $sql = "UPDATE milestones SET completion_state = 1 WHERE id = $milestone_id";

  if ($conn->query($sql) === TRUE) {
    // Update points in the points table
    $date = date('Y-m-d');
    $update_points_sql = "UPDATE points SET points = points + 5 WHERE date = '$date'";

    if ($conn->query($update_points_sql) === TRUE) {
      // Redirect to avoid form resubmission on page refresh
      header("Location: {$_SERVER['REQUEST_URI']}");
      exit();
    } else {
      echo "<p>Error updating points: " . $conn->error . "</p>";
    }
  } else {
    echo "<p>Error updating completion state: " . $conn->error . "</p>";
  }
}

// Retrieve data for today's milestones
$date = date('Y-m-d');
$sql = "SELECT id, activity, milestone, completion_state FROM milestones WHERE date = '$date'";
$result = $conn->query($sql);

$tableBody = ""; // Initialize empty string for table body content

if ($result === false) {
  echo "<p>Error retrieving milestones: " . $conn->error . "</p>";
} else {
  // Check if there are any rows returned
  if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
      $tableBody .= "<tr>";
      $tableBody .= "<td>" . $row["activity"] . "</td>";
      $tableBody .= "<td>" . $row["milestone"] . "</td>";
      // Add a button labeled "Done" for each milestone
      $tableBody .= "<td>";
      if ($row["completion_state"] == 1) {
        $tableBody .= "Completed";
      } else {
        $tableBody .= "<form method='post' action='{$_SERVER['REQUEST_URI']}'>";
        $tableBody .= "<input type='hidden' name='milestone_id' value='" . $row['id'] . "'>";
        $tableBody .= "<button id='donebtn' type='submit'>Done</button>";
        $tableBody .= "</form>";
      }
      $tableBody .= "</td>";
      $tableBody .= "</tr>";
    }
  } else {
    $tableBody = "<tr><td colspan='3'>No milestones found for today.</td></tr>";
  }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Points Tracker</title>
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

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
      table-layout: fixed;
      /* Force table to use fixed layout */
    }

    th,
    td {
      padding: 8px;
      text-align: left;
      border-bottom: 1px solid #ddd;
      height: 40px;
      /* Fixed height for table cells */
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
      color: white;
    }

    #decreaseBtn {
      background-color: #f44336;
      color: white;
    }

    #donebtn {
      padding: 5px 5px;
      width: 100px;
      display: block;
      background-color: #8a2be2;
      /* Purple color */
      color: #fff;
      /* Text color */
      border: none;
      /* Remove border */
      border-radius: 5px;
      /* Rounded corners */

      /* Center horizontally */
    }
  </style>
</head>

<body>
  <!-- Bootstrap Header Section -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Points Tracker</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
          <a class="nav-link" href="./index.php">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Analytics</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="about.html">About</a>
        </li>
      </ul>
    </div>
  </nav>
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
          <th>Completion State</th>
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