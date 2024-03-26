<?php
$servername = "localhost"; // Change this to your MySQL server address
$username = "root"; // Change this to your MySQL username
$password = "root"; // Change this to your MySQL password
$dbname = "wp_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch latest 5 entries from the database
$sql = "SELECT * FROM points ORDER BY date DESC LIMIT 5";
$result = $conn->query($sql);

$insights = array();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $insights[] = $row;
  }
}

// Close connection
$conn->close();

// Return insights data as JSON
header('Content-Type: application/json');
echo json_encode($insights);
