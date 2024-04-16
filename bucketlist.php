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

// Pagination variables
$results_per_page = 5; // Number of items per page
$current_page = 1; // Default current page
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
  $current_page = intval($_GET['page']);
}
$start_from = ($current_page - 1) * $results_per_page;

// Process form data only if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item'])) {
  // Get form data (assuming proper sanitization is done)
  $item = $_POST["item"];
  $description = $_POST["description"];

  // SQL insert statement
  $sql = "INSERT INTO bucketlist (item, description)
            VALUES ('$item', '$description')";

  if ($conn->query($sql) === TRUE) {
    // Redirect to avoid form resubmission on page refresh
    header("Location: bucketlist.php");
    exit();
  } else {
    echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
  }
}

// Process completion of item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete'])) {
  $item_id = $_POST['item_id'];
  $complete_sql = "UPDATE bucketlist SET completed = 1 WHERE id = $item_id";
  if ($conn->query($complete_sql) === TRUE) {
    header("Location: bucketlist.php");
    exit();
  } else {
    echo "<p>Error completing item: " . $conn->error . "</p>";
  }
}

// Retrieve bucket list items with pagination
$bucketlist_sql = "SELECT id, item, description, created_at, completed FROM bucketlist ORDER BY created_at DESC LIMIT $start_from, $results_per_page";
$bucketlist_result = $conn->query($bucketlist_sql);

// Count total number of items for pagination
$total_results_sql = "SELECT COUNT(*) AS total FROM bucketlist";
$total_results_result = $conn->query($total_results_sql);
$total_results_row = $total_results_result->fetch_assoc();
$total_results = $total_results_row['total'];
$total_pages = ($total_results > 0) ? ceil($total_results / $results_per_page) : 1;

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Bucket List</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="style.css">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

  <style>
    .container {
      max-width: 800px;
      margin: 20px auto;
      padding: 20px;
      background-color: #F1EEDC;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    #complete_form{
      display: flex;
      float: right;
    }
    #additembtn{
      width: 200px;
     margin: 0 auto;
    }
  </style>
</head>

<body>
  <!-- Bootstrap Header Section -->
  <?php include 'navbar.php'; ?>
  <div class="container">
    <h1>My Bucket List</h1>
    <form action="" method="post">
      <div class="form-group">
        <label for="item">Item:</label>
        <input type="text" class="form-control" id="item" name="item" required>
      </div>
      <div class="form-group">
        <label for="description">Description:</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
      </div>
      <button id="additembtn"  type="submit" class="btn btn-primary">Add to Bucket List</button>
    </form>
    <br>
    <h4>My Bucket List Items</h4>
    <ul class="list-group">
      <?php if ($bucketlist_result->num_rows > 0) : ?>
        <?php while ($row = $bucketlist_result->fetch_assoc()) : ?>
          <li class="list-group-item">
            <h5><?php echo $row['item']; ?></h5>
            <p><?php echo $row['description']; ?></p>
            <small class="text-muted">Added on: <?php echo $row['created_at']; ?></small>
            <?php if (!$row['completed']) : ?>
              <form id="complete_form" method="post">
                <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                <button id="complete_btn" type="submit" class="btn btn-success complete_btn" name="complete">Complete</button>
              </form>
            <?php else : ?>
              <span class="text-success">Completed</span>
            <?php endif; ?>
          </li>
        <?php endwhile; ?>
      <?php else : ?>
        <li class="list-group-item">No items in the bucket list yet.</li>
      <?php endif; ?>
    </ul>
    <!-- Pagination -->
    <nav aria-label="Page navigation">
      <ul class="pagination justify-content-center">
        <?php for ($page = 1; $page <= $total_pages; $page++) : ?>
          <li class="page-item <?php echo ($page == $current_page) ? 'active' : ''; ?>">
            <a class="page-link" href="bucketlist.php?page=<?php echo $page; ?>"><?php echo $page; ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
  </div>
</body>

</html>