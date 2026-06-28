<?php
session_start();
include 'db.php'; // Rider folder se db include

if(!isset($_SESSION['rider_id'])){
    header("Location: login.php");
    exit();
}

$rider_id = $_SESSION['rider_id'];
$rider_name = $_SESSION['rider_name'];

// Update order status
if(isset($_POST['update_status'])){
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    mysqli_query($con,"UPDATE orders SET status='$new_status', updated_at=NOW() WHERE id='$order_id'");
    header("Location: orders.php");
    exit();
}

// Fetch orders assigned to this rider
$sql_orders = "SELECT o.*, u.name AS customer_name, u.email AS customer_email, r.name AS restaurant_name
               FROM orders o
               JOIN users u ON o.customer_id = u.id
               JOIN order_items oi ON o.id = oi.order_id
               JOIN menu_items m ON oi.menu_id = m.id
               JOIN restaurants r ON m.restaurant_id = r.id
               WHERE o.rider_id='$rider_id'
               GROUP BY o.id
               ORDER BY o.created_at DESC";
$res_orders = mysqli_query($con, $sql_orders);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Rider Assigned Orders</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
body {background:#f9f9f9;}
.card {border-radius:10px; margin-bottom:20px;}
h3 {color:#e67e22;}
</style>
</head>
<body>
<div class="container mt-4">
<h3>🛵 Assigned Orders</h3>
<a href="dashboard.php" class="btn btn-secondary btn-sm mb-3">⬅ Back to Dashboard</a>

<?php
if($res_orders && mysqli_num_rows($res_orders) > 0){
    while($order = mysqli_fetch_assoc($res_orders)){
        echo "<div class='card p-3 shadow-sm mb-3'>
                <h5>Order #".$order['id']." | Status: ".$order['status']."</h5>
                <p><strong>Restaurant:</strong> ".$order['restaurant_name']."</p>
                <p><strong>Customer:</strong> ".$order['customer_name']." | ".$order['customer_email']."</p>
                <p><strong>Placed on:</strong> ".$order['created_at']." | <strong>Payment:</strong> ".$order['payment_method']."</p>";

        // Fetch items
        $order_id = $order['id'];
        $sql_items = "SELECT m.name AS item_name, oi.quantity, oi.price
                      FROM order_items oi
                      JOIN menu_items m ON oi.menu_id = m.id
                      WHERE oi.order_id='$order_id'";
        $res_items = mysqli_query($con, $sql_items);

        if($res_items && mysqli_num_rows($res_items) > 0){
            echo "<table class='table table-bordered'>
                    <tr><th>Item</th><th>Price</th><th>Qty</th><th>Total</th></tr>";
            $order_total = 0;
            while($item = mysqli_fetch_assoc($res_items)){
                $subtotal = $item['price'] * $item['quantity'];
                $order_total += $subtotal;
                echo "<tr>
                        <td>".$item['item_name']."</td>
                        <td>$".$item['price']."</td>
                        <td>".$item['quantity']."</td>
                        <td>$".$subtotal."</td>
                      </tr>";
            }
            echo "<tr>
                    <td colspan='3' class='text-end fw-bold'>Order Total</td>
                    <td class='fw-bold'>$".$order_total."</td>
                  </tr></table>";
        }

        // Status Update Form
        echo "<form method='post'>
                <input type='hidden' name='order_id' value='".$order['id']."'>
                <select name='status' class='form-select d-inline w-auto'>
                    <option ".($order['status']=='pending'?'selected':'').">pending</option>
                    <option ".($order['status']=='picked up'?'selected':'').">picked up</option>
                    <option ".($order['status']=='out for delivery'?'selected':'').">out for delivery</option>
                    <option ".($order['status']=='delivered'?'selected':'').">delivered</option>
                </select>
                <button type='submit' name='update_status' class='btn btn-warning btn-sm'>Update Status</button>
              </form>";

        echo "</div>";
    }
}else{
    echo "<p>No assigned orders found.</p>";
}
?>
</div>
</body>
</html> 