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

// Retrieve data from the database
$cookie_sql = "SELECT timestamp, entry FROM cookiejarentry ORDER BY timestamp DESC";
$cookie_result = $conn->query($cookie_sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cookie Jar</title>
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

    .no-entry {
      color: #6c757d;
      font-style: italic;
    }

    /* Adjust the width of the entry column */
    .entry-column {
      width: 70%;
      /* Adjust the width as needed */
    }

    /* Hide the entries table initially */
    #entriesTable {
      display: none;
    }

    #showEntriesBtn {
      margin-top: 20px;
    }
  </style>
</head>

<body>
  <!-- Navigation Menu -->
  <?php include 'navbar.php'; ?>

  <div class="container">
    <h1>Cookie Jar</h1>
    <p>A cookie jar is a place where you can store your thoughts, memories, or anything else you'd like to remember. Share your entries below!</p>
    <h4>Guidelines:</h4>
    <ol>
      <li><strong>Create your cookie jar:</strong> Start by identifying past successes, big or small. This could be finishing a project, learning a new skill, overcoming a personal obstacle, or even a happy memory. Write them down or collect mementos.</li>
      <li><strong>Fill your jar:</strong> Reflect on these achievements and how they made you feel. Capture the positive emotions and lessons learned.</li>
      <li><strong>Reach into the jar:</strong> When facing challenges, self-doubt, or low motivation, "dip" into your cookie jar. Recall a past success and remind yourself of your resilience and ability to overcome obstacles. This positive boost can help you persevere.</li>
    </ol>

    <form id="cookieForm" action="" method="post">
      <input type="text" name="cookie_entry" id="cookie_entry" placeholder="Write your entry here" required>
      <button type="submit" id="submitEntryBtn">Submit</button>
    </form>

    <!-- Button to show entries -->
    <button id="showEntriesBtn" class="btn btn-primary">Show the Jar</button>
    <br><br>

    <div id="entriesTable"> <!-- Entries table initially hidden -->
      <h4>Entries in the Cookie Jar</h4>
      <table class="table">
        <thead>
          <tr>
            <th>Timestamp</th>
            <th class="entry-column">Entry</th> <!-- Apply custom class to adjust width -->
          </tr>
        </thead>
        <tbody>
          <?php if ($cookie_result->num_rows > 0) : ?>
            <?php while ($row = $cookie_result->fetch_assoc()) : ?>
              <tr>
                <td><?php echo $row['timestamp']; ?></td>
                <td><?php echo $row['entry']; ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else : ?>
            <tr>
              <td colspan="2" class="no-entry">No entries in the cookie jar yet.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <!-- JavaScript to toggle display of entries table -->
  <script>
    $(document).ready(function() {
      $('#showEntriesBtn').click(function() {
        $('#entriesTable').toggle(); // Toggle display of entries table
      });
    });
  </script>
</body>

</html>