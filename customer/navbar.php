<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">FoodSystem</a>
    <div class="d-flex">
      <span class="navbar-text text-white me-3">Welcome, <?php echo $_SESSION['username']; ?></span>
      <a href="logout.php" class="btn btn-light btn-sm">Logout</a>
    </div>
  </div>
</nav>
