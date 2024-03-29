<?php
$servername = "localhost"; // Change this to your MySQL server address
$username = "root"; // Change this to your MySQL username
$password = "root"; // Change this to your MySQL password
$dbname = "wp_db";

// Get points data from the POST request
$points = $_POST['points'];
$date = date("Y-m-d"); // Get the current date in YYYY-MM-DD format

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if a record with the current date exists
$sql = "SELECT * FROM points WHERE date = '$date'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // If a record exists, update the points value
  $row = $result->fetch_assoc();
  $newPoints = $row['points'] + $points;
  $updateSql = "UPDATE points SET points = '$newPoints' WHERE date = '$date'";

  if ($conn->query($updateSql) === TRUE) {
    header('Location: index.php?message=Points updated successfully');
    exit();
  } else {
    echo "Error updating points: " . $conn->error;
  }
} else {
  // If no record exists, insert a new row
  $insertSql = "INSERT INTO points (date, points) VALUES ('$date', '$points')";

  if ($conn->query($insertSql) === TRUE) {
    header('Location: index.php?message=New record inserted successfully');
    exit();
  } else {
    echo "Error inserting record: " . $conn->error;
  }
}

// Close connection
$conn->close();
