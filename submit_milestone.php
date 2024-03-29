<?php
$servername = "localhost"; // Change this to your MySQL server address
$username = "root"; // Change this to your MySQL username
$password = "root"; // Change this to your MySQL password
$dbname = "wp_db";

// Get form data
$activity = $_POST['activity'];
$milestone = $_POST['milestone'];
$date = date("Y-m-d"); // Get the current date in YYYY-MM-DD format

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind SQL statement
$stmt = $conn->prepare("INSERT INTO milestones (date, activity, milestone) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $date, $activity, $milestone);

// Execute SQL statement
if ($stmt->execute() === TRUE) {
  header('Location: index.html?message=milestone added successfully');
  exit();
} else {
  echo "Error adding milestone: " . $conn->error;
}

// Close connections
$stmt->close();
$conn->close();
