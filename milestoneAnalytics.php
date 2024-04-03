Your code seems mostly correct, but I'll make a few adjustments to ensure consistency and readability:

```php
<?php
// Check if the form is submitted and get the active tab parameter
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'milestones';

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

// Initialize $selected_date variable
$selected_date = '';

// Process form data only if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_date'])) {
  // Get selected date from the form
  $selected_date = $_POST['selected_date'];

  // Prepare the SQL statement to retrieve milestones of the selected date
  $milestone_sql = "SELECT activity, completion_state FROM milestones WHERE DATE(date) = ?";

  // Prepare the statement
  $stmt = $conn->prepare($milestone_sql);

  // Bind the parameters
  $stmt->bind_param("s", $selected_date);

  // Execute the statement
  $stmt->execute();

  // Get the result
  $milestone_result = $stmt->get_result();

  // Check if query was successful
  if ($milestone_result === false) {
    echo "<p>Error retrieving milestones: " . $conn->error . "</p>";
  } else {
    // Fetch the data
    $milestone_data = $milestone_result->fetch_all(MYSQLI_ASSOC);
  }

  // Close the statement
  $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Milestone Analytics</title>
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

    .no-milestone {
      color: #6c757d;
      font-style: italic;
    }

    .completed {
      color: green;
    }

    .not-completed {
      color: red;
    }
  </style>
</head>

<body>
  <!-- Bootstrap Header Section -->
  <?php include 'navbar.php'; ?>
  <div class="container">
    <h1>Milestone Analytics</h1>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link <?php if ($active_tab == 'milestones') echo 'active'; ?>" id="milestones-tab" data-toggle="tab" href="#milestones" role="tab" aria-controls="milestones" aria-selected="true">Milestones</a>
      </li>
      <!-- Add other tabs here -->
    </ul>
    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade <?php if ($active_tab == 'milestones') echo 'show active'; ?>" id="milestones" role="tabpanel" aria-labelledby="milestones-tab">
        <form action="" method="post">
          <input type="date" name="selected_date" value="<?php echo $selected_date; ?>" required>
          <button type="submit">Get Milestones</button>
        </form>
        <?php if (isset($milestone_data)) : ?>
          <h4>Milestones for <?php echo $selected_date; ?></h4>
          <table class="table">
            <thead>
              <tr>
                <th>Activity</th>
                <th>Completion State</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($milestone_data)) : ?>
                <?php foreach ($milestone_data as $row) : ?>
                  <tr>
                    <td><?php echo $row['activity']; ?></td>
                    <td class="<?php echo ($row['completion_state'] == 1) ? 'completed' : 'not-completed'; ?>"><?php echo ($row['completion_state'] == 1) ? 'Completed' : 'Not Completed'; ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else : ?>
                <tr>
                  <td colspan="2" class="no-milestone">No milestones found for this date.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
      <!-- Add other tab panes here -->
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://code