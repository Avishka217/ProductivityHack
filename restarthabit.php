<?php

// Connect to database
$conn = mysqli_connect("localhost", "root", "root", "wp_db");

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Get habit ID from POST data
$habit_id = $_POST['habit_id'];

// Check if habit ID is set
if (!isset($habit_id)) {
  echo "Error: Habit ID not provided.";
  exit;
}

// Update habit days in database
$sql = "UPDATE habits SET days = 1 WHERE id = $habit_id";

if (mysqli_query($conn, $sql)) {
  echo "success"; // Send success message on update
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn); // Send error message
}

mysqli_close($conn);
