<?php
session_start();
include 'db.php';

$order_id = $_GET['order_id'] ?? 0;

// Fetch rider coordinates
$sql = "SELECT r.latitude, r.longitude
        FROM orders o
        JOIN riders r ON o.rider_id = r.id
        WHERE o.id='$order_id'";
$res = mysqli_query($con, $sql);
$rider = mysqli_fetch_assoc($res);

echo json_encode($rider);
?>