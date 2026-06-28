<?php
session_start();
include 'db.php';

if(!isset($_SESSION['userid'])){
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['userid'];

// Fetch customer orders
$sql_orders = "SELECT o.*, r.name AS restaurant_name 
               FROM orders o
               JOIN order_items oi ON o.id = oi.order_id
               JOIN menu_items m ON oi.menu_id = m.id
               JOIN restaurants r ON m.restaurant_id = r.id
               WHERE o.customer_id='$customer_id'
               GROUP BY o.id
               ORDER BY o.created_at DESC";
$res_orders = mysqli_query($con, $sql_orders);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Orders</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
body {background:#f9f9f9;}
.card {border-radius:10px; margin-bottom:20px;}
h3 {color:#e67e22;}
</style>
</head>
<body>
<div class="container mt-4">
<h3>🛍 My Orders</h3>

<?php
if($res_orders && mysqli_num_rows($res_orders) > 0){
    while($order = mysqli_fetch_assoc($res_orders)){
        echo "<div class='card p-3 shadow mb-3'>
                <h5>Order #".$order['id']." | Status: ".$order['status']."</h5>
                <p><strong>Restaurant:</strong> ".$order['restaurant_name']."</p>
                <p><strong>Placed on:</strong> ".$order['created_at']."</p>";

        // Show items
        $order_id = $order['id'];
        $sql_items = "SELECT m.name, oi.quantity, oi.price
                      FROM order_items oi
                      JOIN menu_items m ON oi.menu_id = m.id
                      WHERE oi.order_id='$order_id'";
        $res_items = mysqli_query($con, $sql_items);

        if($res_items && mysqli_num_rows($res_items) > 0){
            echo "<table class='table table-bordered'>
                    <tr><th>Item</th><th>Qty</th><th>Price</th><th>Total</th></tr>";
            $order_total = 0;
            while($item = mysqli_fetch_assoc($res_items)){
                $subtotal = $item['price'] * $item['quantity'];
                $order_total += $subtotal;
                echo "<tr>
                        <td>".$item['name']."</td>
                        <td>".$item['quantity']."</td>
                        <td>$".$item['price']."</td>
                        <td>$".$subtotal."</td>
                      </tr>";
            }
            echo "<tr>
                    <td colspan='3' class='text-end fw-bold'>Order Total</td>
                    <td class='fw-bold'>$".$order_total."</td>
                  </tr></table>";
        }

        // Status Badge
        if($order['status']=='pending') echo "<span class='badge bg-warning'>Pending</span>";
        elseif($order['status']=='preparing') echo "<span class='badge bg-info'>Preparing</span>";
        elseif($order['status']=='out for delivery') echo "<span class='badge bg-primary'>Out for Delivery</span>";
        elseif($order['status']=='delivered') echo "<span class='badge bg-success'>Delivered</span>";
        elseif($order['status']=='cancelled') echo "<span class='badge bg-danger'>Cancelled</span>";

        echo "</div>";
    }
}else{
    echo "<p>You have no orders yet.</p>";
}
?>
</div>
</body>
</html> rider order