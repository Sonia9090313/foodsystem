<?php
session_start();
include 'db.php';

if(!isset($_SESSION['userid'])){
    header("Location: login.php");
    exit();
}

if(isset($_GET['order_id'])){
    $order_id = intval($_GET['order_id']);
    $customer_id = $_SESSION['userid'];

    // Sirf apne hi order cancel kar sake
    $sql_check = "SELECT * FROM orders WHERE id='$order_id' AND customer_id='$customer_id' AND status IN ('pending','preparing')";
    $res_check = mysqli_query($con, $sql_check);

    if($res_check && mysqli_num_rows($res_check) > 0){
        // Update status
        $sql_update = "UPDATE orders SET status='cancelled', updated_at=NOW() WHERE id='$order_id'";
        if(mysqli_query($con, $sql_update)){
            echo "<script>alert('✅ Order has been cancelled successfully.'); window.location.href='my_order.php';</script>";
        } else {
            echo "<script>alert('❌ Failed to cancel order.'); window.location.href='my_order.php';</script>";
        }
    } else {
        echo "<script>alert('⚠ Order cannot be cancelled (maybe already delivered or cancelled).'); window.location.href='my_orders.php';</script>";
    }
} else {
    header("Location: my_order.php");
    exit();
}
?>