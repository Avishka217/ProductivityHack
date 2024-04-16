<?php
// restarthabit.php

// Check if habit_id is set in POST data
if (isset($_POST['habit_id'])) {
  // Sanitize habit_id to prevent SQL injection
  $habit_id = intval($_POST['habit_id']);

  // Connect to the database
  $conn = mysqli_connect("localhost", "root", "root", "wp_db");

  // Check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // SQL query to update habit data (you need to adjust this query based on your database schema)
  $sql = "UPDATE habits SET start_date = NOW() WHERE id = $habit_id";

  // Execute the query
  if (mysqli_query($conn, $sql)) {
    // If the query was successful, echo 'success'
    echo 'success';
  } else {
    // If there was an error with the query, echo 'error'
    echo 'error';
  }

  // Close the database connection
  mysqli_close($conn);
} else {
  // If habit_id is not set in POST data, echo 'error'
  echo 'error';
}
