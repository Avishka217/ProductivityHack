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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recollection'])) {
  // Get current timestamp with time
  $timestamp = date('Y-m-d H:i:s');

  // Get form data (assuming proper sanitization is done)
  $recollection = $_POST["recollection"];

  // SQL insert statement
  $sql = "INSERT INTO recollections (timestamp, recollection)
            VALUES ('$timestamp', '$recollection')";

  if ($conn->query($sql) === TRUE) {
    // Redirect to avoid form resubmission on page refresh
    header("Location: recalltheday.php");
    exit();
  } else {
    echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
  }
}

// Retrieve recollection of the day for the current date
$current_date = date('Y-m-d');
$recollection_sql = "SELECT timestamp, recollection FROM recollections WHERE DATE(timestamp) = '$current_date'";
$recollection_result = $conn->query($recollection_sql);

$recollection_text = ""; // Initialize empty string for recollection content

if ($recollection_result === false) {
  echo "<p>Error retrieving recollection: " . $conn->error . "</p>";
} else {
  // Check if there are any rows returned
  if ($recollection_result->num_rows > 0) {
    $row = $recollection_result->fetch_assoc();
    $recollection_text = $row["recollection"];
  } else {
    $recollection_text = "No recollection found for today.";
  }
}

$conn->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recall the Day</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 800px;
      margin: 20px auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1,
    h4 {
      color: #007bff;
    }

    form input[type="text"] {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      border: 1px solid #ced4da;
      border-radius: 5px;
      margin-bottom: 20px;
      box-sizing: border-box;
    }

    form button[type="submit"] {
      padding: 10px 20px;
      font-size: 16px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      background-color: #007bff;
      color: #fff;
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

    .no-recollection {
      color: #6c757d;
      font-style: italic;
    }
  </style>
</head>

<body>
  <!-- Bootstrap Header Section -->
  <?php include 'navbar.php'; ?>
  <div class="container">
    <h1>Recall the Day</h1>
    <form action="" method="post">
      <input type="text" name="recollection" id="recollection" placeholder="Recollections of the day" required>
      <button type="submit">Submit</button>
    </form>
    <br>
    <h4>This happened today</h4>
    <table class="table">
      <thead>
        <tr>
          <th>Timestamp</th>
          <th>Recollection</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($recollection_text !== "No recollection found for today.") : ?>
          <?php while ($row = $recollection_result->fetch_assoc()) : ?>
            <tr>
              <td><?php echo $row['timestamp']; ?></td>
              <td><?php echo $row['recollection']; ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else : ?>
          <tr>
            <td colspan="2" class="no-recollection"><?php echo $recollection_text; ?></td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>

</html>