<?php
session_start();
include 'db.php';

// Admin login check
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Order ID check
if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = (int)$_GET['id'];

// Order fetch with customer + restaurant info
$sql = "SELECT o.*, u.name AS customer_name, u.email AS customer_email, 
               r.name AS restaurant_name, r.email AS restaurant_email
        FROM orders o
        JOIN users u ON o.customer_id=u.id
        JOIN restaurants r ON o.restaurant_id=r.id
        WHERE o.id='$order_id'";
$res = mysqli_query($con, $sql);
$order = mysqli_fetch_assoc($res);

if ($order) {
    // Status update
    $update = "UPDATE orders SET status='cancelled' WHERE id='$order_id'";
    if (mysqli_query($con, $update)) {

        // Email Notifications
        $to_customer = $order['customer_email'];
        $to_restaurant = $order['restaurant_email'];

        $subject = "Order #{$order['id']} Cancelled";
        $headers = "From: no-reply@foodsystem.com\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $message_customer = "
            <h2>Dear {$order['customer_name']},</h2>
            <p>Your order <strong>#{$order['id']}</strong> has been <b>cancelled</b>.</p>
            <p>If you paid online, refund will be processed soon.</p>
            <br><p>Thank you, FoodSystem Team</p>
        ";

        $message_restaurant = "
            <h2>Hello {$order['restaurant_name']},</h2>
            <p>Order <strong>#{$order['id']}</strong> has been <b>cancelled by admin</b>.</p>
            <p>No action required.</p>
            <br><p>Regards, FoodSystem Team</p>
        ";

        // Send email
        @mail($to_customer, $subject, $message_customer, $headers);
        @mail($to_restaurant, $subject, $message_restaurant, $headers);

        $_SESSION['success'] = "Order cancelled successfully.";
    } else {
        $_SESSION['error'] = "Something went wrong while cancelling order.";
    }
} else {
    $_SESSION['error'] = "Order not found.";
}

// Redirect
header("Location: orders.php");
exit();
?>