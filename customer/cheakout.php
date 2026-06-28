<?php
session_start();
if(!isset($_SESSION['userid'])){
    header("Location: login.php");
    exit();
}
include 'db.php';

$customer_id = $_SESSION['userid'];

$sql = "SELECT c.id AS cart_id, m.id AS menu_id, m.name, m.price, c.quantity, r.name AS restaurant_name
        FROM cart c
        JOIN menu_items m ON c.menu_id = m.id
        JOIN restaurants r ON c.restaurant_id = r.id
        WHERE c.customer_id='$customer_id'";
$result = mysqli_query($con, $sql);

$total_cart = 0;
$items = [];
if($result && mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
        $subtotal = $row['price'] * $row['quantity'];
        $total_cart += $subtotal;
        $items[] = $row;
    }
}

// Place order
if(isset($_POST['place_order'])){
    $payment_method = mysqli_real_escape_string($con, $_POST['payment_method']);
    if($total_cart > 0){
        $sql_order = "INSERT INTO orders (customer_id, total_amount, status, payment_method, created_at)
                      VALUES ('$customer_id','$total_cart','pending','$payment_method', NOW())";
        if(mysqli_query($con, $sql_order)){
            $order_id = mysqli_insert_id($con);
            foreach($items as $it){
                $menu_id = $it['menu_id'];
                $qty = $it['quantity'];
                $price = $it['price'];
                mysqli_query($con, "INSERT INTO order_items (order_id, menu_id, quantity, price) VALUES ('$order_id','$menu_id','$qty','$price')");
            }
            mysqli_query($con, "DELETE FROM cart WHERE customer_id='$customer_id'");
            echo "<script>alert('✅ Order placed successfully!'); window.location='my_order.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error placing order: ".mysqli_error($con)."');</script>";
        }
    } else {
        echo "<script>alert('Your cart is empty!'); window.location='cart.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Checkout</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
body {
  background:#f4f6f9;
  font-family:'Segoe UI', sans-serif;
  margin:0;
  padding:0;
}

/* === Dashboard Style Sidebar === */
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

/* === Checkout Form Styling === */
h3 { color:#e67e22; font-weight:600; }
.table th {
  background:#e67e22;
  color:#fff;
}
.btn-orange {
  background:#e67e22;
  color:#fff;
}
.btn-orange:hover {
  background:#cf711f;
}

/* === Footer === */
footer {
  background-color:#e67e22;
  color:white;
  text-align:center;
  padding:10px 0;
  margin-top:40px;
  font-weight:500;
  letter-spacing:0.5px;
  box-shadow:0 -2px 5px rgba(0,0,0,0.1);
}
</style>
</head>
<body>
 <?php include 'sidebar.php'; ?>

<!-- Main Content -->
<div class="main-content">
  <h3>Checkout</h3>
  <!-- <a href="cart.php" class="btn btn-secondary btn-sm mb-3">⬅ Back to Cart</a> -->

  <?php if($total_cart>0){ ?>
  <form method="post">
  <table class="table table-bordered">
  <tr><th>Item</th><th>Restaurant</th><th>Price</th><th>Qty</th><th>Total</th></tr>
  <?php foreach($items as $row){ ?>
  <tr>
  <td><?= htmlspecialchars($row['name']); ?></td>
  <td><?= htmlspecialchars($row['restaurant_name']); ?></td>
  <td>$<?= $row['price']; ?></td>
  <td><?= $row['quantity']; ?></td>
  <td>$<?= $row['price']*$row['quantity']; ?></td>
  </tr>
  <?php } ?>
  <tr>
  <td colspan="4" class="text-end fw-bold">Grand Total</td>
  <td class="fw-bold">$<?= $total_cart; ?></td>
  </tr>
  </table>

  <div class="mb-3">
  <label>Payment Method:</label>
  <select name="payment_method" class="form-select" required>
  <option value="">Select Payment</option>
  <option value="COD">Cash on Delivery</option>
  <option value="Online">Online Payment</option>
  </select>
  </div>

  <button type="submit" name="place_order" class="btn btn-orange btn-lg">Place Order</button>
  </form>
  <?php } else { ?>
  <p>Your cart is empty.</p>
  <?php } ?>

   <?php include 'footer.php'; ?>
</div>

</body>
</html>