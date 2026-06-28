<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Customer Dashboard</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="sidebar.css">
<link rel="stylesheet" href="footer.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
  <div class="card p-4">
    <h4 class="mb-3" style="color:#e67e22;">Welcome to Your Dashboard</h4>
    <p>Here you can browse restaurants, place orders, and track your food delivery in real-time.</p>
    <hr>
    <h5>Quick Actions</h5>
    <div class="row">
      <div class="col-md-4 mb-3">
        <div class="card text-center p-3 shadow-sm">
          <h6>Browse Restaurants</h6>
          <a href="restaurant_list.php" class="btn btn-warning btn-sm mt-2">Go</a>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card text-center p-3 shadow-sm">
          <h6>My Orders</h6>
          <a href="my_order.php" class="btn btn-warning btn-sm mt-2">View</a>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card text-center p-3 shadow-sm">
          <h6>Profile</h6>
          <a href="profile.php" class="btn btn-warning btn-sm mt-2">Edit</a>
        </div>
      </div>
    </div>
  </div>

  <?php include 'footer.php'; ?>
</div>

</body>
</html>