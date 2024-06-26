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

// Handle deletion of milestone
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_milestone'])) {
  $milestone_id = $_POST['milestone_id'];

  // SQL delete statement to delete the milestone
  $delete_sql = "DELETE FROM milestones WHERE id = $milestone_id";

  if ($conn->query($delete_sql) === TRUE) {
    // Update points in the points table by reducing 5 points
    $date = date('Y-m-d');
    $update_points_sql = "UPDATE points SET points = points - 5 WHERE date = '$date'";

    if ($conn->query($update_points_sql) === TRUE) {
      // Redirect to avoid form resubmission on page refresh
      header("Location: {$_SERVER['REQUEST_URI']}");
      exit();
    } else {
      echo "<p>Error updating points: " . $conn->error . "</p>";
    }
  } else {
    echo "<p>Error deleting milestone: " . $conn->error . "</p>";
  }
}

// Handle marking milestone as completed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_completed'])) {
  $milestone_id = $_POST['milestone_id'];

  // SQL update statement to mark the milestone as completed
  $update_sql = "UPDATE milestones SET completion_state = 1 WHERE id = $milestone_id";

  if ($conn->query($update_sql) === TRUE) {
    // Check if there's an entry in the points table for the current date
    $date = date('Y-m-d');
    $check_points_sql = "SELECT * FROM points WHERE date = '$date'";
    $result = $conn->query($check_points_sql);

    if ($result->num_rows == 0) {
      // If no entry exists, create a new entry for the current date
      $insert_points_sql = "INSERT INTO points (date, points) VALUES ('$date', 5)";
      if ($conn->query($insert_points_sql) !== TRUE) {
        echo "<p>Error creating points entry: " . $conn->error . "</p>";
      }
    } else {
      // If an entry exists, update the points by adding 5 points
      $update_points_sql = "UPDATE points SET points = points + 5 WHERE date = '$date'";
      if ($conn->query($update_points_sql) !== TRUE) {
        echo "<p>Error updating points: " . $conn->error . "</p>";
      }
    }

    // Redirect to avoid form resubmission on page refresh
    header("Location: {$_SERVER['REQUEST_URI']}");
    exit();
  } else {
    echo "<p>Error marking milestone as completed: " . $conn->error . "</p>";
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
      $tableBody .= "<td class='completed' style='color:green;'>";
      if ($row["completion_state"] == 1) {
        $tableBody .= "<b>Completed</b>";
      } else {
        $tableBody .= "<form method='post' action='{$_SERVER['REQUEST_URI']}'>";
        $tableBody .= "<input type='hidden' name='milestone_id' value='" . $row['id'] . "'>";
        $tableBody .= "<button id='donebtn' type='submit' name='mark_completed'>✓</button>";
        $tableBody .= "</form>";
      }
      // Add a close button for deleting milestone
      $tableBody .= "</td>";
      $tableBody .= "<td>";
      if ($row["completion_state"] != 1) {
        $tableBody .= "<form method='post' action='{$_SERVER['REQUEST_URI']}'>";
        $tableBody .= "<input type='hidden' name='milestone_id' value='" . $row['id'] . "'>";
        $tableBody .= "<button type='submit' name='delete_milestone' class='close-btn'>X</button>";
        $tableBody .= "</form>";
      }
      $tableBody .= "</td>";
    }
  } else {
    $tableBody = "<tr><td colspan='4'>No milestones found for today.</td></tr>";
  }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ProductivityHack</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      /* Set a standard web font */
      margin: 0;
      padding: 0;
      background-image: url('./images/background.jpg');
      /* Add your doodle background image */
      background-size: cover;
      /* Cover the entire viewport */
    }

    /* Overlay with 70% opacity */
    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 255, 255, 0.8);
      /* White with 70% opacity */
      z-index: -1;
      /* Place it behind other content */
    }


    body {
      font-family: Arial, sans-serif;
      /* Set a standard web font */
      margin: 0;
      padding: 0;
    }

    td button {
      margin-right: 5px;
      /* Reduce margin between buttons */
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

    .completed {
      text-align: center;
      /* Center the text horizontally */
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
      background-color: #F1EEDC;
    }

    h1,
    h2,
    h3,
    h4 {
      margin-top: 0;
      font-weight: 600;
      color: #8a2be2;
      border-top: 1px solid purple;
      padding-top: 18px;
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

    #increaseBtn:hover {
      background-color: #45a049;
      /* Darker shade of green */
      color: #fff;
      /* White text */
    }

    #decreaseBtn {
      background-color: #f44336;
      color: white;
    }

    #decreaseBtn:hover {
      background-color: #d32f2f;
      /* Darker shade of red */
      color: #fff;
      /* White text */
    }

    #donebtn {
      padding: 5px 5px;
      width: 50px;
      display: block;
      background-color: #2be231;
      margin-left: 38px;
      /* Purple color */
      color: #fff;
      /* Text color */
      border: none;
      /* Remove border */
      border-radius: 5px;
      /* Rounded corners */
    }

    /* Center horizontally */

    /* Add CSS for close button */
    .close-btn {
      width: 50px;
      padding: 5px 5px;
      font-size: 14px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      background-color: #dc3545;

      /* Red color */
      color: #fff;
      transition: background-color 0.3s;
    }

    .close-btn:hover {
      background-color: #c82333;
      /* Darker red color on hover */
    }

    #milestones-table>tr>td {

      height: 70px;
    }

    #submitBtn {
      width: 30%;
      display: block;
      margin: 0 auto;
    }
  </style>
</head>

<body>
  <!-- Bootstrap Header Section -->

  <?php include 'navbar.php'; ?>
  <div class="container">
    <h1>Distraction KickMeter </h1>
    <div id="pointsDisplay">
     <b> Will Power Credits: </b><span id="points" style="font-weight: 
     800;">0</span>
    </div>
    <button id="increaseBtn">Avoided a distraction</button>
    <button id="decreaseBtn">Coudn't</button>
    <form id="pointsForm" action="submit.php" method="post">
      <input type="hidden" id="pointsInput" name="points">
      <button type="submit" id="submitBtn">Submitt !</button>
    </form>

    <h4> Points for avoiding distrations over the last 5 days </h4>
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

    <h4>Add Milestone</h4>
    <p style="font-size: 12px; color: #666; text-align: left;">Guidelines:</p>
    <ol style="font-size: 12px; color: #666; text-align: left;">
      <li>When you complete a milestone, it adds 5 points to the daily points.</li>
      <li>When you cancel a milestone, it reduces 5 points.</li>
      <li>If you keep a milestone without completing or canceling, no marks will be added. This is useful to track your progress on ongoing tasks.</li>
    </ol>
    <form action="" method="post">
      <input type="text" name="activity" id="activity" placeholder="Add an activity" required>
      <input type="text" name="milestone" id="milestone" placeholder="Milestone" required>

      <button type="submit" id="submitBtn">Add Milestone</button>
    </form>

    <h4>Today's Milestones</h4>
    <table>
      <thead>
        <tr>

          <th>Activity</th>
          <th>Milestone</th>
          <th>Completion State</th>
          <th>Cancel</th>
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