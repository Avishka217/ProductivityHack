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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['direction'])) {
  // Get current date
  $date = date('Y-m-d');

  // Check if a direction already exists for the current date
  $existing_direction_sql = "SELECT * FROM directionday WHERE date = '$date'";
  $existing_direction_result = $conn->query($existing_direction_sql);

  if ($existing_direction_result->num_rows > 0) {
    echo "<p style='color:red;'>Error: Only one entry is allowed for each day.</p>";
  } else {
    // Get form data (assuming proper sanitization is done)
    $direction = $_POST["direction"];

    // SQL insert statement
    $sql = "INSERT INTO directionday (date, direction)
            VALUES ('$date', '$direction')";

    if ($conn->query($sql) === TRUE) {
      // Redirect to avoid form resubmission on page refresh
      header("Location: directionday.php");
      exit();
    } else {
      echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
  }
}

// Retrieve direction of the day for the last seven days
$direction_sql = "SELECT date, direction FROM directionday ORDER BY date DESC LIMIT 7";
$direction_result = $conn->query($direction_sql);

$tableBody = ""; // Initialize empty string for table body content

if ($direction_result === false) {
  echo "<p>Error retrieving directions: " . $conn->error . "</p>";
} else {
  // Check if there are any rows returned
  if ($direction_result->num_rows > 0) {
    // Output data of each row
    while ($row = $direction_result->fetch_assoc()) {
      $tableBody .= "<tr>";
      $tableBody .= "<td>" . $row["date"] . "</td>";
      $tableBody .= "<td>" . $row["direction"] . "</td>";
      $tableBody .= "</tr>";
    }
  } else {
    $tableBody = "<tr><td colspan='2'>No directions found for the last seven days.</td></tr>";
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Direction of the Day</title>
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

    form {
      margin-bottom: 20px;
    }

    input[type="text"],
    input[type="submit"] {
      padding: 10px;
      font-size: 16px;
      border: 1px solid #ddd;
      border-radius: 5px;
      margin-bottom: 10px;
      width: 100%;
      box-sizing: border-box;
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

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
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
  </style>
</head>

<body>
  <!-- Bootstrap Header Section -->

  <?php include 'navbar.php'; ?>
  <div class="container">
    <h1>Add Direction of the Day</h1>
    <form action="" method="post">
      <label for="direction">Direction:</label>
      <input type="text" name="direction" id="direction" required>
      <br>
      <button type="submit" id="submitBtn">Add Direction</button>
    </form>

    <h2>Directions of the Last Seven Days</h2>
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Direction</th>
        </tr>
      </thead>
      <tbody>
        <?php echo $tableBody; ?>
      </tbody>
    </table>
  </div>
</body>

</html>