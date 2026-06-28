<?php
session_start();
include 'db.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

// Fetch all orders with customer & restaurant info
$sql_orders = "SELECT o.*, u.name AS customer_name, u.email AS customer_email, r.name AS restaurant_name
               FROM orders o
               JOIN users u ON o.customer_id=u.id
               JOIN restaurants r ON o.restaurant_id=r.id
               ORDER BY o.created_at DESC";
$res_orders = mysqli_query($con, $sql_orders);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Orders</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {background:#f9f9f9; font-family:'Segoe UI', sans-serif;}

/* Sidebar same as dashboard */
.sidebar {
    width:220px;
    background:#ff7f50;
    min-height:100vh;
    position:fixed;
    color:#fff;
    padding-top:20px;
    display:flex;
    flex-direction:column;
}
.sidebar h4 {text-align:center; margin-bottom:20px;}
.sidebar a {
    display:flex; align-items:center; gap:10px;
    padding:12px 20px; color:#fff; text-decoration:none; margin-bottom:5px; border-radius:8px;
}
.sidebar a:hover {background:#ff5722;}

/* Main content */
.main-content {margin-left:240px; padding:20px;}
h3 {color:#FF7F50; margin-bottom:20px;}
.table th {background:#FF7F50; color:#fff;}
.btn-orange, .btn-orange:hover {background:#FF7F50; color:#fff; border:none;}
.btn-secondary {background:#6c757d; color:#fff;}
.card {border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1); margin-bottom:20px;}
.badge-pending {background:#FFA07A; color:#fff;}
.badge-preparing {background:#FF8C00; color:#fff;}
.badge-out {background:#20B2AA; color:#fff;}
.badge-delivered {background:#28a745; color:#fff;}
.badge-cancelled {background:#dc3545; color:#fff;}
</style>
</head>
<body>

<!-- Sidebar (dashboard style) -->
<?php include "navbar.php" ?>

<!-- Main Content -->
<div class="main-content">
    <h3>🛒 All Orders</h3>

    <?php if($res_orders && mysqli_num_rows($res_orders) > 0): ?>
        <?php while($order = mysqli_fetch_assoc($res_orders)): ?>
            <div class="card">
                <div class="card-body">
                    <h5>Order #<?= $order['id']; ?> | 
                        <?php 
                            $status = $order['status'];
                            $badge_class = match($status){
                                'pending'=>'badge-pending',
                                'preparing'=>'badge-preparing',
                                'out'=>'badge-out',
                                'delivered'=>'badge-delivered',
                                'cancelled'=>'badge-cancelled',
                                default=>'badge-secondary'
                            };
                        ?>
                        <span class="badge <?= $badge_class ?>"><?= ucfirst($status); ?></span>
                    </h5>
                    <p>Customer: <?= htmlspecialchars($order['customer_name']); ?> | <?= htmlspecialchars($order['customer_email']); ?></p>
                    <p>Restaurant: <?= htmlspecialchars($order['restaurant_name']); ?> | Placed on: <?= $order['created_at']; ?></p>
                    <p>Payment: <?= htmlspecialchars($order['payment_method']); ?></p>

                    <?php
                    // Fetch order items
                    $order_id = $order['id'];
                    $sql_items = "SELECT m.name AS item_name, oi.quantity, oi.price
                                  FROM order_items oi
                                  JOIN menu_items m ON oi.menu_id=m.id
                                  WHERE oi.order_id='$order_id'";
                    $res_items = mysqli_query($con, $sql_items);
                    if($res_items && mysqli_num_rows($res_items) > 0):
                        $order_total = 0;
                    ?>
                        <table class="table table-bordered mt-2">
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

                    <!-- Actions -->
                    <a href="order_details.php?id=<?= $order['id']; ?>" class="btn btn-orange btn-sm"><i class="bi bi-eye"></i> Details</a>
                    <?php if(in_array($status,['pending','preparing'])): ?>
                        <a href="order_cancel.php?id=<?= $order['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Cancel this order?')"><i class="bi bi-x-circle"></i> Cancel</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>
</div>

</body>
</html>