<?php
session_start();
include 'db.php';

if(!isset($_SESSION['restaurant_id'])){
    header("Location: login.php");
    exit();
}

$restaurant_id = $_SESSION['restaurant_id'];

if(isset($_GET['order_id'], $_GET['action'])){
    $order_id = intval($_GET['order_id']);
    $action = $_GET['action'];

    $valid_status = ['accept'=>'preparing', 'reject'=>'cancelled', 'complete'=>'delivered'];

    if(array_key_exists($action, $valid_status)){
        $new_status = $valid_status[$action];
        $sql = "UPDATE orders SET status='$new_status' WHERE id='$order_id'";
        mysqli_query($con, $sql);
    }
}

header("Location: orders.php");
exit();