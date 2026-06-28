<?php
session_start();
include 'db.php'; // DB connection
if(!isset($_SESSION['userid'])){
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['userid'];
$menu_id = intval($_POST['menu_id']);
$restaurant_id = intval($_POST['restaurant_id']);
$quantity = intval($_POST['quantity']);

// Check if this item is already in cart for this customer
$sql_check = "SELECT * FROM cart WHERE customer_id='$customer_id' AND menu_id='$menu_id'";
$res_check = mysqli_query($con, $sql_check);

if(mysqli_num_rows($res_check) > 0){
    // Already in cart → Update quantity
    mysqli_query($con,"UPDATE cart SET quantity = quantity + $quantity WHERE customer_id='$customer_id' AND menu_id='$menu_id'");
} else {
    // Not in cart → Insert new row
    mysqli_query($con,"INSERT INTO cart (customer_id, menu_id, restaurant_id, quantity) VALUES ('$customer_id','$menu_id','$restaurant_id','$quantity')");
}

echo "<script>alert('✅ Item added to cart!'); window.location='cart.php';</script>";
exit();
?>