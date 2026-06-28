<?php
session_start();
include 'db.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

// Fetch total users
$res_users = mysqli_query($con, "SELECT COUNT(*) AS total FROM users");
$total_users = mysqli_fetch_assoc($res_users)['total'];

// Fetch total restaurants (from restaurants table)
$res_restaurants = mysqli_query($con, "SELECT COUNT(*) AS total FROM restaurants");
$total_restaurants = mysqli_fetch_assoc($res_restaurants)['total'];

// Fetch total riders (from riders table)
$res_riders = mysqli_query($con, "SELECT COUNT(*) AS total FROM riders");
$total_riders = mysqli_fetch_assoc($res_riders)['total'];

// Fetch total orders
$res_orders = mysqli_query($con, "SELECT COUNT(*) AS total FROM orders");
$total_orders = mysqli_fetch_assoc($res_orders)['total'];

// Fetch total revenue
$res_revenue = mysqli_query($con, "SELECT SUM(total_amount) AS revenue FROM orders WHERE status='delivered'");
$total_revenue = mysqli_fetch_assoc($res_revenue)['revenue'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
    background: #f0f2f5;
    font-family: 'Segoe UI', sans-serif;
}
.sidebar {
    width: 220px;
    background: #ff7f50;
    min-height: 100vh;
    position: fixed;
    color: #fff;
    padding-top: 20px;
}
.sidebar a {
    display: block;
    padding: 12px 20px;
    color: #fff;
    text-decoration: none;
    margin-bottom: 5px;
    border-radius: 8px;
}
.sidebar a:hover {
    background: #ff5722;
}
.main-content {
    margin-left: 240px;
    padding: 20px;
}
.card {
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.card h5 {
    font-weight: 600;
}
.card i {
    font-size: 2rem;
}
</style>
</head>
<body>


<?php include "navbar.php" ?>


<div class="main-content">
    <h3 class="mb-4 text-secondary">Welcome, Admin!</h3>
    <div class="row g-4">

        <div class="col-md-3">
            <div class="card p-3 text-center" style="background:#ffccbc;">
                <i class="bi bi-people-fill text-dark"></i>
                <h5 class="mt-2">Total Users</h5>
                <p class="fs-4 fw-bold"><?php echo $total_users; ?></p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 text-center" style="background:#ffe0b2;">
                <i class="bi bi-shop-window text-dark"></i>
                <h5 class="mt-2">Restaurants</h5>
                <p class="fs-4 fw-bold"><?php echo $total_restaurants; ?></p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 text-center" style="background:#d1c4e9;">
                <i class="bi bi-bicycle text-dark"></i>
                <h5 class="mt-2">Riders</h5>
                <p class="fs-4 fw-bold"><?php echo $total_riders; ?></p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 text-center" style="background:#b2dfdb;">
                <i class="bi bi-cart-check text-dark"></i>
                <h5 class="mt-2">Orders</h5>
                <p class="fs-4 fw-bold"><?php echo $total_orders; ?></p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 text-center mt-4" style="background:#fff59d;">
                <i class="bi bi-currency-dollar text-dark"></i>
                <h5 class="mt-2">Revenue</h5>
                <p class="fs-4 fw-bold">$<?php echo $total_revenue; ?></p>
            </div>
        </div>

    </div>
</div>

</body>
</html>