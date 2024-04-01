<!-- navbar.php -->
<?php
// Get the current page filename
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">Points Tracker</a>
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
      <li class="nav-item <?php echo ($currentPage === 'analytics.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="#">Analytics</a>
      </li>
      <li class="nav-item <?php echo ($currentPage === 'about.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="about.php">About</a>
      </li>

    </ul>
  </div>
</nav>