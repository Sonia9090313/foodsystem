<?php
session_start();
if(!isset($_SESSION['userid'])){
    header("Location: login.php");
    exit();
}

include 'db.php';

if(isset($_GET['id'])){
    $cart_id = intval($_GET['id']);
    mysqli_query($con, "DELETE FROM cart WHERE id='$cart_id'");
}

header("Location: cart.php");
exit();
?>
