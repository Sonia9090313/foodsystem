<?php
session_start();
include 'db.php';

if(!isset($_SESSION['userid'])){
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['userid'];
$is_guest = strpos($customer_id, 'guest_') === 0;

if(!$is_guest){
    $sql_orders = "SELECT * FROM orders WHERE customer_id='$customer_id' ORDER BY created_at DESC";
    $res_orders = mysqli_query($con, $sql_orders);
} else {
    $res_orders = false; 
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Orders</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
body {
  background:#f4f6f9;
  font-family:'Segoe UI', sans-serif;
  margin:0;
  padding:0;
}
.sidebar {
  width:230px;
  background:#e67e22;
  color:#fff;
  position:fixed;
  top:0;
  left:0;
  height:100vh;
  padding:20px 15px;
  box-shadow:2px 0 8px rgba(0,0,0,0.1);
}
.sidebar h4 {
  text-align:center;
  font-weight:bold;
  margin-bottom:30px;
}
.sidebar a {
  display:block;
  color:#fff;
  padding:10px 15px;
  margin-bottom:5px;
  text-decoration:none;
  border-radius:6px;
  transition:0.3s;
}
.sidebar a:hover, .sidebar a.active {
  background:#cf711f;
}
.main-content {
  margin-left:250px;
  padding:30px;
}
.card {
  border-radius:10px;
  box-shadow:0 2px 6px rgba(0,0,0,0.1);
}
footer {
  margin-top:40px;
  background-color:#e67e22;
  color:white;
  text-align:center;
  padding:10px 0;
  font-weight:500;
  font-size:15px;
  letter-spacing:0.5px;
  box-shadow:0 -2px 5px rgba(0,0,0,0.1);
}
footer span {
  color:#fff;
}
.table th {
  background:#e67e22;
  color:#fff;
}
.btn-orange {background:#e67e22; color:#fff;}
.btn-orange:hover {background:#cf711f;}
.alert {margin-top:10px;}
</style>
</head>
<body>
 

<?php include 'sidebar.php'; ?>
 
<div class="main-content">
  <h3 class="mb-3" style="color:#e67e22;">🛍 My Orders</h3>
 
<?php

if(!$is_guest && $res_orders && mysqli_num_rows($res_orders) > 0){
    while($order = mysqli_fetch_assoc($res_orders)){
        echo "<div class='card p-3 mb-3'>
                <h5>Order #".$order['id']." | Status: ".htmlspecialchars($order['status'])."</h5>
                <p>Placed on: ".$order['created_at']." | Payment: ".htmlspecialchars($order['payment_method'])."</p>";

        $order_id = $order['id'];
        $sql_items = "SELECT oi.quantity, oi.price, m.name, r.name AS restaurant_name 
                      FROM order_items oi
                      JOIN menu_items m ON oi.menu_id = m.id
                      JOIN restaurants r ON m.restaurant_id = r.id
                      WHERE oi.order_id='$order_id'";
        $res_items = mysqli_query($con, $sql_items);

        if($res_items && mysqli_num_rows($res_items) > 0){
            echo "<table class='table table-bordered'>
                    <tr><th>Item</th><th>Restaurant</th><th>Price</th><th>Qty</th><th>Total</th></tr>";
            $order_total = 0;
            while($item = mysqli_fetch_assoc($res_items)){
                $subtotal = $item['price'] * $item['quantity'];
                $order_total += $subtotal;
                echo "<tr>
                        <td>".htmlspecialchars($item['name'])."</td>
                        <td>".htmlspecialchars($item['restaurant_name'])."</td>
                        <td>$".$item['price']."</td>
                        <td>".$item['quantity']."</td>
                        <td>$".$subtotal."</td>
                      </tr>";
            }
            echo "<tr>
                    <td colspan='4' class='text-end fw-bold'>Order Total</td>
                    <td class='fw-bold'>$".$order_total."</td>
                  </tr>";
            echo "</table>";
        }

        if($order['status'] == 'cancelled'){
            echo "<div class='alert alert-danger'>Your order #".$order['id']." has been cancelled!</div>";
        } elseif($order['status'] == 'delivered'){
            echo "<div class='alert alert-success'>Your order #".$order['id']." has been delivered!</div>";
        }

        if($order['status'] == 'out for delivery' && !empty($order['rider_id'])){
            if(!empty($order['latitude']) && !empty($order['longitude'])){
                echo "<a href='rider_tracking.php?order_id=".$order['id']."' class='btn btn-orange me-2'>Track Rider</a>";
            } else {
                echo "<div class='alert alert-warning'>Rider assigned but location not updated yet!</div>";
            }
        }

        if(in_array($order['status'], ['pending','preparing'])){
            echo "<a href='cancel_order.php?order_id=".$order['id']."' 
                     class='btn btn-danger'
                     onclick=\"return confirm('Are you sure you want to cancel this order?');\">Cancel Order</a>";
        }

        echo "</div>";
    }
}

if($is_guest){
    if(isset($_SESSION['guest_orders']) && count($_SESSION['guest_orders']) > 0){
        foreach($_SESSION['guest_orders'] as $order){
            echo "<div class='card p-3 mb-3'>
                    <h5>Order #".$order['id']." | Status: ".$order['status']."</h5>
                    <p>Placed on: ".$order['created_at']." | Payment: ".$order['payment_method']."</p>";

            echo "<table class='table table-bordered'>
                    <tr><th>Item</th><th>Restaurant</th><th>Price</th><th>Qty</th><th>Total</th></tr>";
            $order_total = 0;
            foreach($order['items'] as $item){
                $subtotal = $item['price'] * $item['quantity'];
                $order_total += $subtotal;
                echo "<tr>
                        <td>".$item['name']."</td>
                        <td>".$item['restaurant_name']."</td>
                        <td>$".$item['price']."</td>
                        <td>".$item['quantity']."</td>
                        <td>$".$subtotal."</td>
                      </tr>";
            }
            echo "<tr>
                    <td colspan='4' class='text-end fw-bold'>Order Total</td>
                    <td class='fw-bold'>$".$order_total."</td>
                  </tr></table>";

            echo "<div class='alert alert-secondary'>Guest orders cannot be cancelled</div>";

            echo "</div>";
        }
    } else {
        echo "<p>You have no orders yet.</p>";
    }
}

if(!$is_guest && $res_orders && mysqli_num_rows($res_orders) == 0){
    echo "<p>You have no orders yet.</p>";
}
?>

  <footer>
    <span>© 2025 FoodSystem. All rights reserved.</span>
  </footer>
</div>

</body>
</html>