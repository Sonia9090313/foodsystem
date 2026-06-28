<?php
session_start();
include 'db.php';

if(!isset($_SESSION['restaurant_id'])){
    header("Location: login.php");
    exit();
}

$restaurant_id = $_SESSION['restaurant_id'];
$restaurant_name = $_SESSION['restaurant_name'];

$total_orders = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(*) AS total FROM orders WHERE restaurant_id='$restaurant_id'"))['total'] ?? 0;
$pending_orders = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(*) AS total FROM orders WHERE restaurant_id='$restaurant_id' AND status='pending'"))['total'] ?? 0;
$completed_orders = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(*) AS total FROM orders WHERE restaurant_id='$restaurant_id' AND status='delivered'"))['total'] ?? 0;

$profile = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM restaurants WHERE id='$restaurant_id'"));
$logo = $profile['logo'] ?? 'default_logo.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Restaurant Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
  font-family: 'Segoe UI', sans-serif;
  background: #f4f6f9;
}
.sidebar {
  width: 240px;
  background: #e67e22;
  min-height: 100vh;
  position: fixed;
  color: #fff;
  padding-top: 20px;
}
.sidebar a {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 20px;
  color: #fff;
  text-decoration: none;
  margin-bottom: 5px;
  border-radius: 8px;
  transition: .3s;
}
.sidebar a:hover {
  background: #cf711f;
}
.sidebar i {
  font-size: 1.2rem;
}
.main-content {
  margin-left: 260px;
  padding: 20px;
}
.card {
  border-radius: 15px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.card h4 {
  color: #e67e22;
}
.profile-logo {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 50%;
  margin-bottom: 10px;
  border: 2px solid #fff;
}
.stats-card {
  text-align: center;
  padding: 30px 20px;
  border-radius: 15px;
  background: #fff;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.quick-links .btn {
  width: 100%;
}
.quick-links .row > div {
  display: flex;
  justify-content: center;
}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar text-center">
    <img src="uploads/<?php echo $logo; ?>" class="profile-logo" alt="Logo">
    <h5 class="mt-2 mb-4"><?php echo htmlspecialchars($restaurant_name); ?></h5>
    <a href="dashboard.php"><i class="bi bi-speedometer2"></i> <span>Dashboard</span></a>
    <a href="orders.php"><i class="bi bi-cart"></i> <span>Orders</span></a>
    <a href="menu.php"><i class="bi bi-list-ul"></i> <span>Menu Items</span></a>
    <a href="profile.php"><i class="bi bi-person-circle"></i> <span>Profile</span></a>
    <a href="analytics.php"><i class="bi bi-bar-chart-line"></i> <span>Analytics</span></a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> <span>Logout</span></a>
</div>

<!-- Main content -->
<div class="main-content">
    <h3 class="mb-4">👋 Welcome, <?php echo htmlspecialchars($restaurant_name); ?></h3>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stats-card">
                <h4>Total Orders</h4>
                <h2><?php echo $total_orders; ?></h2>
                <i class="bi bi-bag-fill" style="font-size:2rem;color:#e67e22;"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <h4>Pending Orders</h4>
                <h2><?php echo $pending_orders; ?></h2>
                <i class="bi bi-clock-fill" style="font-size:2rem;color:#e67e22;"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <h4>Completed Orders</h4>
                <h2><?php echo $completed_orders; ?></h2>
                <i class="bi bi-check2-circle" style="font-size:2rem;color:#e67e22;"></i>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="card p-4 mb-4 quick-links">
        <h5 class="mb-3">Quick Actions</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <a href="menu.php" class="btn btn-warning"><i class="bi bi-list-ul me-1"></i> Manage Menu</a>
            </div>
            <div class="col-md-6">
                <a href="orders.php" class="btn btn-warning"><i class="bi bi-cart me-1"></i> View Orders</a>
            </div>
            <div class="col-md-6">
                <a href="profile.php" class="btn btn-warning"><i class="bi bi-person-circle me-1"></i> Update Profile</a>
            </div>
            <div class="col-md-6">
                <a href="analytics.php" class="btn btn-warning"><i class="bi bi-bar-chart-line me-1"></i> Analytics</a>
            </div>
        </div>
    </div>

    <!-- Profile Info -->
    <div class="card p-4">
        <h5>Restaurant Info</h5>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($profile['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($profile['email']); ?></p>
        <p><strong>Contact:</strong> <?php echo htmlspecialchars($profile['contact']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($profile['address']); ?></p>
        <p><strong>GPS:</strong> Lat <?php echo $profile['lat'] ?? '-'; ?>, Lng <?php echo $profile['lng'] ?? '-'; ?></p>
    </div>
</div>

</body>
</html>
