<?php
// Get the current page filename
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Analytics</title>
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
  </style>
</head>

<body>
  <!-- Bootstrap Header Section -->
  <?php include 'navbar.php'; ?>
  <div class="container">
    <h1>Analytics</h1>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link <?php echo ($currentPage === 'analytics.php' || $currentPage === 'recallAnalytics.php') ? 'active' : ''; ?>" id="recollections-tab" data-toggle="tab" href="#recollections" role="tab" aria-controls="recollections" aria-selected="true">Recollections Analytics</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo ($currentPage === 'milestoneAnalytics.php') ? 'active' : ''; ?>" id="milestones-tab" data-toggle="tab" href="#milestones" role="tab" aria-controls="milestones" aria-selected="false">Milestone Analytics</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo ($currentPage === 'points.php') ? 'active' : ''; ?>" id="points-tab" data-toggle="tab" href="#points" role="tab" aria-controls="points" aria-selected="false">Points Analytics</a>
      </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade <?php echo ($currentPage === 'analytics.php' || $currentPage === 'recallAnalytics.php') ? 'show active' : ''; ?>" id="recollections" role="tabpanel" aria-labelledby="recollections-tab">
        <?php include 'recallAnalytics.php'; ?>
      </div>
      <div class="tab-pane fade <?php echo ($currentPage === 'milestoneAnalytics.php') ? 'show active' : ''; ?>" id="milestones" role="tabpanel" aria-labelledby="milestones-tab">
        <?php include 'milestoneAnalytics.php'; ?>
      </div>
      <div class="tab-pane fade <?php echo ($currentPage === 'points.php') ? 'show active' : ''; ?>" id="points" role="tabpanel" aria-labelledby="points-tab">
        <?php include 'points.php'; ?>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS and jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>