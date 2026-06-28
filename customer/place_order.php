<?php
session_start();
if(!isset($_SESSION['userid'])){
    header("Location: login.php");
    exit();
}
include 'db.php';

$customer_id = $_SESSION['userid'];
$restaurant_id = $_POST['restaurant_id'];

// Fetch cart
$sql_cart = "SELECT * FROM cart WHERE customer_id='$customer_id'";
$res_cart = mysqli_query($con, $sql_cart);

if(mysqli_num_rows($res_cart) == 0){
    echo "<script>alert('Your cart is empty!'); window.location='cart.php';</script>";
    exit();
}

// Calculate total
$total = 0;
while($item = mysqli_fetch_assoc($res_cart)){
    $menu_id = $item['menu_id'];
    $q = $item['quantity'];
    $price_res = mysqli_query($con, "SELECT price FROM menu_items WHERE id='$menu_id'");
    $price_row = mysqli_fetch_assoc($price_res);
    $price = $price_row['price'];
    $total += $price * $q;
}

// Insert order
mysqli_query($con, "INSERT INTO orders (customer_id, restaurant_id, total_amount) VALUES ('$customer_id','$restaurant_id','$total')");
$order_id = mysqli_insert_id($con);

// Insert order items
$res_cart = mysqli_query($con, $sql_cart);
while($item = mysqli_fetch_assoc($res_cart)){
    $menu_id = $item['menu_id'];
    $q = $item['quantity'];
    $price_res = mysqli_query($con, "SELECT price FROM menu_items WHERE id='$menu_id'");
    $price_row = mysqli_fetch_assoc($price_res);
    $price = $price_row['price'];

    mysqli_query($con, "INSERT INTO order_items (order_id, menu_id, quantity, price) 
                        VALUES ('$order_id','$menu_id','$q','$price')");
}

// Clear cart
mysqli_query($con, "DELETE FROM cart WHERE customer_id='$customer_id'");

echo "<script>alert('Order placed successfully!'); window.location='my_order.php';</script>";
