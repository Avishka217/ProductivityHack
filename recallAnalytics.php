<?php
// Database connection details (replace with your actual details)
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "wp_db";
date_default_timezone_set('Asia/Kolkata');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Process form data only if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_date'])) {
  // Get selected date from the form
  $selected_date = $_POST['selected_date'];

  // Retrieve recollections of the selected date
  $recollection_sql = "SELECT timestamp, recollection FROM recollections WHERE DATE(timestamp) = '$selected_date'";
  $recollection_result = $conn->query($recollection_sql);

  $recollection_text = ""; // Initialize empty string for recollection content

  if ($recollection_result === false) {
    echo "<p>Error retrieving recollections: " . $conn->error . "</p>";
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recollections Analytics</title>
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

    form input[type="date"] {
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
  <!-- <?php include 'navbar.php'; ?> -->
  <div class="container">
    <h1>Recollections Analytics</h1>
    <form action="" method="post">
      <input type="date" name="selected_date" required>
      <button type="submit">Get Recollections</button>
    </form>
    <?php if (isset($recollection_result)) : ?>
      <h4>Recollections for <?php echo $selected_date; ?></h4>
      <table class="table">
        <thead>
          <tr>
            <th>Timestamp</th>
            <th>Recollection</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($recollection_result->num_rows > 0) : ?>
            <?php while ($row = $recollection_result->fetch_assoc()) : ?>
              <tr>
                <td><?php echo $row['timestamp']; ?></td>
                <td><?php echo $row['recollection']; ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else : ?>
            <tr>
              <td colspan="2" class="no-recollection">No recollections found for this date.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</body>

</html>