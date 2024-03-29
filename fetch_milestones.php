<?php
// Connect to your database
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "wp_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get current date
$currentDate = date("Y-m-d");

// Prepare SQL statement to retrieve milestones for the current date
$sql = "SELECT activity, milestone FROM milestones WHERE date = '$currentDate'";
$result = $conn->query($sql);

// Prepare array to store fetched milestones
$milestones = array();

// Fetch and store milestones in the array
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $milestones[] = $row;
  }
}

// Close database connection
$conn->close();

// Return milestones as JSON
header('Content-Type: application/json');
echo json_encode($milestones);
