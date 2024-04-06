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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cookie_entry'])) {
  // Get current timestamp with time
  $timestamp = date('Y-m-d H:i:s');

  // Get form data (assuming proper sanitization is done)
  $cookie_entry = $_POST["cookie_entry"];

  // SQL insert statement
  $sql = "INSERT INTO cookiejarentry (timestamp, entry)
            VALUES ('$timestamp', '$cookie_entry')";

  if ($conn->query($sql) === TRUE) {
    // Redirect to avoid form resubmission on page refresh
    header("Location: cookiejar.php");
    exit();
  } else {
    echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
  }
}
