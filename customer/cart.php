<?php
session_start();
if(!isset($_SESSION['userid'])){
    header("Location: login.php");
    exit();
}
include 'db.php';

$customer_id = $_SESSION['userid'];

$sql = "SELECT c.id AS cart_id, m.name, m.price, c.quantity, r.name AS restaurant_name
        FROM cart c
        JOIN menu_items m ON c.menu_id = m.id
        JOIN restaurants r ON c.restaurant_id = r.id
        WHERE c.customer_id='$customer_id'";
$result = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Cart</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
body {
  background:#f4f6f9;
  font-family:'Segoe UI', sans-serif;
  margin:0;
  padding:0;
}
.main-content {
  margin-left:250px;
  padding:30px;
}

/* Table */
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
</style>
<script>
function confirmRemove(id){
  if(confirm("Are you sure you want to remove this item?")){
    window.location.href = "remove_cart.php?id=" + id;
  }
}
</script>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
  <h3>🛒 My Cart</h3>
  <table class="table table-bordered mt-3">
    <tr>
      <th>Item</th>
      <th>Restaurant</th>
      <th>Price</th>
      <th>Quantity</th>
      <th>Total</th>
      <th>Action</th>
    </tr>

    <?php
    $total_cart = 0;
    if($result && mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $subtotal = $row['price'] * $row['quantity'];
            $total_cart += $subtotal;
            echo "<tr>
                    <td>".htmlspecialchars($row['name'])."</td>
                    <td>".htmlspecialchars($row['restaurant_name'])."</td>
                    <td>$".$row['price']."</td>
                    <td>".$row['quantity']."</td>
                    <td>$".$subtotal."</td>
                    <td><button onclick='confirmRemove(".$row['cart_id'].")' class=\"btn btn-danger btn-sm\">Remove</button></td>
                  </tr>";
        }
        echo "<tr>
                <td colspan='4' class='text-end fw-bold'>Total</td>
                <td colspan='2' class='fw-bold'>$".$total_cart."</td>
              </tr>";
        echo "<tr>
                <td colspan='6' class='text-end'>
                  <a href='cheakout.php' class='btn btn-orange btn-lg'>Proceed to Checkout</a>
                </td>
              </tr>";
    } else {
        echo "<tr><td colspan='6' class='text-center'>Your cart is empty</td></tr>";
    }
    ?>
  </table>

  <?php include 'footer.php'; ?>
</div>

</body>
</html>