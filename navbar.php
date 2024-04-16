<?php
// Get the current page filename
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">Productivity | <span style="color:yellow;">Hack</span></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item <?php echo ($currentPage === 'index.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="./index.php">Home</a>
      </li>
      <li class="nav-item <?php echo ($currentPage === 'directionday.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="directionday.php">Direction of the Day</a>
      </li>
      <li class="nav-item <?php echo ($currentPage === 'recalltheday.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="recalltheday.php">Recall the Day</a>
      </li>
      <li class="nav-item <?php echo ($currentPage === 'cookiejar.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="cookiejar.php">Cookie Jar</a>
      </li>
      <li class="nav-item <?php echo ($currentPage === 'habits.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="habits.php">Habits</a>
      </li>
      <li class="nav-item <?php echo ($currentPage === 'bucketlist.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="bucketlist.php">Bucket List</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle <?php echo ($currentPage === 'analytics.php' || $currentPage === 'recallAnalytics.php' || $currentPage === 'milestoneAnalytics.php') ? 'active' : ''; ?>" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Analytics
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item <?php echo ($currentPage === 'recallAnalytics.php') ? 'active' : ''; ?>" href="recallAnalytics.php">Recall Analytics</a>
          <a class="dropdown-item <?php echo ($currentPage === 'milestoneAnalytics.php') ? 'active' : ''; ?>" href="milestoneAnalytics.php">Milestone Analytics</a>
        </div>
      </li>
      <li class="nav-item <?php echo ($currentPage === 'about.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="about.php">About</a>
      </li>
    </ul>
  </div>
</nav>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- JavaScript to initialize Bootstrap components -->
<script>
  $(document).ready(function() {
    // Initialize Bootstrap dropdowns
    $('.dropdown-toggle').dropdown();
  });
</script>