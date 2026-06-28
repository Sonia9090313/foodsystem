<?php
session_start();
include 'db.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: orders.php");
    exit();
}

$order_id = (int)$_GET['id'];

// Fetch order with customer & restaurant info
$sql_order = "SELECT o.*, u.name AS customer_name, u.email AS customer_email, r.name AS restaurant_name
              FROM orders o
              JOIN users u ON o.customer_id=u.id
              JOIN restaurants r ON o.restaurant_id=r.id
              WHERE o.id='$order_id'";
$res_order = mysqli_query($con, $sql_order);
$order = mysqli_fetch_assoc($res_order);

// Fetch order items
$sql_items = "SELECT m.name AS item_name, oi.quantity, oi.price
              FROM order_items oi
              JOIN menu_items m ON oi.menu_id=m.id
              WHERE oi.order_id='$order_id'";
$res_items = mysqli_query($con, $sql_items);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Details</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {background:#f9f9f9;
 font-family:'Segoe UI', sans-serif;
}
.sidebar {width:220px;
 background:#FF7F50;
  min-height:100vh;
   position:fixed; 
   color:#fff; 
   padding-top:20px; 
   display:flex; 
   flex-direction:column;
}
.sidebar h4 {text-align:center;
 margin-bottom:20px;
}
.sidebar a {display:flex;
 align-items:center; 
 gap:10px;
  padding:12px 20px;
   color:#fff;
    text-decoration:none; 
    margin-bottom:5px; 
    border-radius:8px;
}
.sidebar a:hover {
    background:#e26f47;
}
.main-content {
    margin-left:240px;
     padding:20px;}
h3 {color:#FF7F50;
 margin-bottom:20px;
}
.table th {background:#FF7F50; 
    color:#fff;
}
.card {border-radius:10px;
 box-shadow:0 4px 10px rgba(0,0,0,0.1);
  margin-bottom:20px;
}
.btn-orange, .btn-orange:hover {
    background:#FF7F50;
     color:#fff;
      border:none;
  }
</style>
</head>
<body>


<?php include "navbar.php" ?>

<div class="main-content">
    <h3>📦 Order Details</h3>
    <a href="orders.php" class="btn btn-secondary btn-sm mb-3"><i class="bi bi-arrow-left"></i> Back</a>

    <?php if($order): ?>
    <div class="card p-3">
        <h5>Order #<?= $order['id']; ?> | Status: <?= ucfirst($order['status']); ?></h5>
        <p><strong>Placed on:</strong> <?= $order['created_at']; ?> | <strong>Payment:</strong> <?= htmlspecialchars($order['payment_method']); ?></p>
        <p><strong>Customer:</strong> <?= htmlspecialchars($order['customer_name']); ?> (<?= htmlspecialchars($order['customer_email']); ?>)</p>
        <p><strong>Restaurant:</strong> <?= htmlspecialchars($order['restaurant_name']); ?></p>

        <?php if($res_items && mysqli_num_rows($res_items) > 0): 
            $order_total = 0;
        ?>
            <table class="table table-bordered mt-3">
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
                <?php while($item = mysqli_fetch_assoc($res_items)):
                    $subtotal = $item['price'] * $item['quantity'];
                    $order_total += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['item_name']); ?></td>
                    <td>$<?= $item['price']; ?></td>
                    <td><?= $item['quantity']; ?></td>
                    <td>$<?= $subtotal; ?></td>
                </tr>
                <?php endwhile; ?>
                <tr>
                    <td colspan="3" class="text-end fw-bold">Order Total</td>
                    <td class="fw-bold">$<?= $order_total; ?></td>
                </tr>
            </table>
        <?php endif; ?>

        <!-- Cancel Order Button -->
        <?php if(in_array($order['status'], ['pending','preparing'])): ?>
            <a href="order_cancel.php?id=<?= $order['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Cancel this order?')">
                <i class="bi bi-x-circle"></i> Cancel Order
            </a>
        <?php endif; ?>
    </div>
    <?php else: ?>
        <p>Order not found.</p>
    <?php endif; ?>
</div>

</body>
</html>