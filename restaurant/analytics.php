<?php
session_start();
include 'db.php';

if(!isset($_SESSION['restaurant_id'])){
    header("Location: login.php");
    exit();
}

$restaurant_id = $_SESSION['restaurant_id'];
$restaurant_name = $_SESSION['restaurant_name'];

// 🔸 Get restaurant logo
$sql_logo = "SELECT logo FROM restaurants WHERE id='$restaurant_id'";
$res_logo = mysqli_query($con, $sql_logo);
$row_logo = mysqli_fetch_assoc($res_logo);
$logo = $row_logo['logo'] ?? 'default.png'; // fallback if no logo

// 🔸 Total Orders
$sql_total_orders = "SELECT COUNT(*) AS total 
                     FROM orders o 
                     JOIN order_items oi ON o.id=oi.order_id 
                     JOIN menu_items m ON oi.menu_id=m.id 
                     WHERE m.restaurant_id='$restaurant_id'";
$res_total_orders = mysqli_query($con, $sql_total_orders);
$total_orders = ($res_total_orders) ? mysqli_fetch_assoc($res_total_orders)['total'] ?? 0 : 0;

// 🔸 Pending Orders
$sql_pending = "SELECT COUNT(*) AS total 
                FROM orders o 
                JOIN order_items oi ON o.id=oi.order_id 
                JOIN menu_items m ON oi.menu_id=m.id 
                WHERE m.restaurant_id='$restaurant_id' AND o.status='pending'";
$res_pending = mysqli_query($con, $sql_pending);
$pending_orders = ($res_pending) ? mysqli_fetch_assoc($res_pending)['total'] ?? 0 : 0;

// 🔸 Completed Orders
$sql_completed = "SELECT COUNT(*) AS total 
                  FROM orders o 
                  JOIN order_items oi ON o.id=oi.order_id 
                  JOIN menu_items m ON oi.menu_id=m.id 
                  WHERE m.restaurant_id='$restaurant_id' AND o.status='delivered'";
$res_completed = mysqli_query($con, $sql_completed);
$completed_orders = ($res_completed) ? mysqli_fetch_assoc($res_completed)['total'] ?? 0 : 0;

// 🔸 Total Revenue
$sql_revenue = "SELECT SUM(oi.price * oi.quantity) AS revenue 
                FROM order_items oi 
                JOIN menu_items m ON oi.menu_id=m.id 
                JOIN orders o ON oi.order_id=o.id 
                WHERE m.restaurant_id='$restaurant_id' AND o.status='delivered'";
$res_revenue = mysqli_query($con, $sql_revenue);
$total_revenue = ($res_revenue) ? mysqli_fetch_assoc($res_revenue)['revenue'] ?? 0 : 0;

// 🔸 Average Rating
$sql_rating = "SELECT AVG(rating) AS avg_rating 
               FROM reviews rv 
               JOIN menu_items m ON rv.menu_id=m.id 
               WHERE m.restaurant_id='$restaurant_id'";
$res_rating = mysqli_query($con, $sql_rating);
$avg_rating = ($res_rating) ? round(mysqli_fetch_assoc($res_rating)['avg_rating'] ?? 0, 1) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Restaurant Analytics</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
  font-family: 'Segoe UI', sans-serif;
  background: #f4f6f9;
  margin: 0;
  padding: 0;
}

/* ✅ Exact same sidebar as restaurant dashboard */
.sidebar {
    width: 240px;
    background: #e67e22;
    color: #fff;
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    padding-top: 20px;
    text-align: center;
    box-shadow: 2px 0 8px rgba(0,0,0,0.1);
}
.sidebar img.profile-logo {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 10px;
    border: 2px solid #fff;
}
.sidebar h5 {
    margin-bottom: 20px;
    font-weight: 600;
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
    transition: 0.3s;
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

/* Cards */
.card {
  border-radius: 15px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  height: 100%;
  transition: transform 0.2s;
}
.card:hover {
  transform: translateY(-5px);
}
.card h4 {
  color: #e67e22;
}
.card i {
  font-size: 2rem;
  color: #e67e22;
}
</style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<!-- <div class="sidebar">
    <img src="uploads/<?php echo htmlspecialchars($logo); ?>" class="profile-logo" alt="Logo">
    <h5><?php echo htmlspecialchars($restaurant_name); ?></h5>
    <a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="orders.php"><i class="bi bi-cart"></i> Orders</a>
    <a href="menu.php"><i class="bi bi-list-ul"></i> Menu Items</a>
    <a href="profile.php"><i class="bi bi-person-circle"></i> Profile</a>
    <a href="analytics.php"><i class="bi bi-bar-chart-line"></i> Analytics</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div> -->

<!-- Main Content -->
<div class="main-content">
    <div class="navbar d-flex justify-content-between align-items-center mb-4">
        <h5>Analytics Overview 📊</h5>
        <span class="text-muted">Restaurant Dashboard</span>
    </div>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="card p-4 text-center d-flex flex-column justify-content-center">
                <h4>Total Orders</h4>
                <h2><?php echo $total_orders; ?></h2>
                <i class="bi bi-bag-fill"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 text-center d-flex flex-column justify-content-center">
                <h4>Pending Orders</h4>
                <h2><?php echo $pending_orders; ?></h2>
                <i class="bi bi-clock-fill"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 text-center d-flex flex-column justify-content-center">
                <h4>Completed Orders</h4>
                <h2><?php echo $completed_orders; ?></h2>
                <i class="bi bi-check2-circle"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 text-center d-flex flex-column justify-content-center">
                <h4>Total Revenue</h4>
                <h2>$<?php echo number_format($total_revenue, 2); ?></h2>
                <i class="bi bi-currency-dollar"></i>
            </div>
        </div>
        <div class="col-md-3 mt-3">
            <div class="card p-4 text-center d-flex flex-column justify-content-center">
                <h4>Avg. Rating</h4>
                <h2><?php echo $avg_rating; ?> ⭐</h2>
                <i class="bi bi-star-fill"></i>
            </div>
        </div>
    </div>
</div>

</body>
</html>