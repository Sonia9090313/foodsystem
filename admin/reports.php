<?php
session_start();
include 'db.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}


$res_users = mysqli_query($con, "SELECT COUNT(*) AS total FROM users");
$total_users = mysqli_fetch_assoc($res_users)['total'];


$res_restaurants = mysqli_query($con, "SELECT COUNT(*) AS total FROM restaurants");
$total_restaurants = mysqli_fetch_assoc($res_restaurants)['total'];


$res_riders = mysqli_query($con, "SELECT COUNT(*) AS total FROM riders");
$total_riders = mysqli_fetch_assoc($res_riders)['total'];


$res_orders = mysqli_query($con, "SELECT COUNT(*) AS total FROM orders");
$total_orders = mysqli_fetch_assoc($res_orders)['total'];


$res_earnings = mysqli_query($con, "SELECT SUM(total_amount) AS total FROM orders WHERE status='delivered'");
$total_earnings = mysqli_fetch_assoc($res_earnings)['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {background:#f0f2f5; font-family:'Segoe UI', sans-serif;}


.sidebar {
    width:220px;
     background:#FF7F50;
      min-height:100vh; 
      position:fixed; 
      color:#fff;
       padding-top:20px;
}
.sidebar h4 {text-align:center; 
    margin-bottom:20px;
}
.sidebar a {display:block;
 padding:12px 20px; 
 color:#fff;
  text-decoration:none;
   margin-bottom:5px;
    border-radius:8px;
}
.sidebar a:hover {background:#ff5722;
}


.main-content {margin-left:240px;
 padding:20px;
}
h3 {color:#FF7F50;
 margin-bottom:20px;
}


.card {
    border-radius:15px;
     box-shadow:0 5px 15px rgba(0,0,0,0.1);
    text-align:center; 
    padding:20px;
}
.card i {font-size:2.5rem; 
    margin-bottom:10px;
}
</style>
</head>
<body>

<!-- Sidebar (dashboard style) -->
<?php include "navbar.php" ?>

<div class="main-content">
    <h3>📊 Admin Dashboard</h3>
    <div class="row g-4">

       
        <div class="col-md-3">
            <div class="card" style="border-left:6px solid #007bff;">
                <i class="bi bi-people-fill text-primary"></i>
                <h5 class="mt-2">Total Users</h5>
                <h3><?php echo $total_users; ?></h3>
            </div>
        </div>

      
        <div class="col-md-3">
            <div class="card" style="border-left:6px solid #28a745;">
                <i class="bi bi-shop-window text-success"></i>
                <h5 class="mt-2">Restaurants</h5>
                <h3><?php echo $total_restaurants; ?></h3>
            </div>
        </div>

      
        <div class="col-md-3">
            <div class="card" style="border-left:6px solid #ffc107;">
                <i class="bi bi-bicycle text-warning"></i>
                <h5 class="mt-2">Riders</h5>
                <h3><?php echo $total_riders; ?></h3>
            </div>
        </div>

      
        <div class="col-md-3">
            <div class="card" style="border-left:6px solid #fd7e14;">
                <i class="bi bi-cart-check text-orange"></i>
                <h5 class="mt-2">Orders</h5>
                <h3><?php echo $total_orders; ?></h3>
            </div>
        </div>

       
        <div class="col-md-3 mt-4">
            <div class="card" style="border-left:6px solid #dc3545;">
                <i class="bi bi-currency-dollar text-danger"></i>
                <h5 class="mt-2">Earnings</h5>
                <h3>$<?php echo number_format($total_earnings,2); ?></h3>
            </div>
        </div>

    </div>
</div>

</body>
</html>