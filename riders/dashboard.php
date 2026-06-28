<?php 
session_start();
include 'db.php'; 

if (!isset($_SESSION['rider_id'])) {
    header("Location: login.php");
    exit();
}

$rider_id = (int) $_SESSION['rider_id'];
$rider_name = htmlspecialchars($_SESSION['rider_name'] ?? 'Rider');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = (int) $_POST['order_id'];
    $new_status = mysqli_real_escape_string($con, $_POST['status']);
    mysqli_query($con, "UPDATE orders SET status='$new_status', updated_at=NOW() WHERE id='$order_id' AND rider_id='$rider_id'");
    header("Location: dashboard.php");
    exit();
}

function fetch_count($con, $sql) {
    $res = mysqli_query($con, $sql);
    if (!$res) return 0;
    $row = mysqli_fetch_assoc($res);
    return intval($row['total'] ?? 0);
}

$total_orders = fetch_count($con, "SELECT COUNT(*) AS total FROM orders WHERE rider_id='$rider_id'");
$pending_orders = fetch_count($con, "SELECT COUNT(*) AS total FROM orders WHERE rider_id='$rider_id' AND status IN ('pending','picked up','out for delivery')");
$completed_orders = fetch_count($con, "SELECT COUNT(*) AS total FROM orders WHERE rider_id='$rider_id' AND status='delivered'");

// Fetch assigned orders
$sql_orders = "SELECT * FROM orders WHERE rider_id='$rider_id' ORDER BY created_at DESC";
$res_orders = mysqli_query($con, $sql_orders);
$orders = [];
if ($res_orders) {
    while ($o = mysqli_fetch_assoc($res_orders)) $orders[] = $o;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Rider Dashboard</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body { 
  background:#f4f6f9;
  font-family: 'Segoe UI', sans-serif; 
  margin: 0;
}

/* --- Sidebar --- */
.sidebar {
  width: 230px;
  background: #e67e22;
  color: #fff;
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-top: 30px;
  box-shadow: 2px 0 8px rgba(0,0,0,0.1);
}

.sidebar h4 {
  font-size: 18px;
  margin-bottom: 40px;
  text-align: center;
}

.sidebar a {
  color: #fff;
  text-decoration: none;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 85%;
  padding: 12px 0;
  margin: 5px 0;
  text-align: center;
  font-weight: 500;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.sidebar a i {
  margin-right: 8px;
}

.sidebar a:hover {
  background: rgba(255,255,255,0.2);
}

/* --- Main Content --- */
.main-content {
  margin-left: 250px;
  padding: 20px;
}

.card {
  border-radius: 10px;
  margin-bottom: 15px;
}

.stats-card {
  background: #fff; 
  padding: 20px; 
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  text-align: center; 
}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h4><i class="fas fa-motorcycle"></i> <?php echo $rider_name; ?></h4>
  <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
  <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
  <h3>🛵 Rider Dashboard</h3>

  <div class="row mb-4">
    <div class="col-md-4"><div class="stats-card"><h5>Total Orders</h5><h2><?php echo $total_orders; ?></h2></div></div>
    <div class="col-md-4"><div class="stats-card"><h5>Pending</h5><h2><?php echo $pending_orders; ?></h2></div></div>
    <div class="col-md-4"><div class="stats-card"><h5>Completed</h5><h2><?php echo $completed_orders; ?></h2></div></div>
  </div>

  <h5>Assigned Orders</h5>
  <?php if (count($orders) === 0) { ?>
    <p>No assigned orders.</p>
  <?php } else {
    foreach ($orders as $order) {
      $order_id = (int)$order['id'];
      $res_items = mysqli_query($con, "SELECT oi.quantity, oi.price, m.name FROM order_items oi JOIN menu_items m ON oi.menu_id=m.id WHERE oi.order_id='$order_id'");
      $items = [];
      $order_total = 0;
      if ($res_items) {
         while ($it = mysqli_fetch_assoc($res_items)) {
             $items[] = $it;
             $order_total += $it['price'] * $it['quantity'];
         }
      }
  ?>
    <div class="card p-3 shadow-sm">
      <div class="d-flex justify-content-between flex-wrap">
        <div>
          <h6>Order #<?php echo $order_id; ?> 
            <small class="text-muted">| <?php echo htmlspecialchars($order['status']); ?></small>
          </h6>
          <p><strong>Placed:</strong> <?php echo $order['created_at']; ?> | 
             <strong>Payment:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
          <ul>
            <?php foreach($items as $it){ ?>
              <li><?php echo htmlspecialchars($it['name']); ?> — <?php echo $it['quantity']; ?> × $<?php echo $it['price']; ?></li>
            <?php } ?>
          </ul>
          <p class="fw-bold">Total: $<?php echo number_format($order_total,2); ?></p>
        </div>
        <div style="min-width:220px;">
          <form method="post" style="margin-bottom:10px;">
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
            <select name="status" class="form-select mb-2">
                <option value="pending" <?php if($order['status']=='pending') echo 'selected'; ?>>pending</option>
                <option value="picked up" <?php if($order['status']=='picked up') echo 'selected'; ?>>picked up</option>
                <option value="out for delivery" <?php if($order['status']=='out for delivery') echo 'selected'; ?>>out for delivery</option>
                <option value="delivered" <?php if($order['status']=='delivered') echo 'selected'; ?>>delivered</option>
            </select>
            <button type="submit" name="update_status" class="btn btn-warning w-100">Update Status</button>
          </form>
          <a href="../restaurant/track_rider.php?order_id=<?php echo $order_id; ?>" class="btn btn-info w-100 mb-1" target="_blank">View on Map</a>
        </div>
      </div>
    </div>
  <?php } } ?>
</div>

<script>
function sendLocation() {
  if (!navigator.geolocation) return;
  navigator.geolocation.getCurrentPosition(function(pos){
    var lat = pos.coords.latitude;
    var lng = pos.coords.longitude;
    var xhr = new XMLHttpRequest();
    xhr.open("POST","update_location.php",true);
    xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    xhr.send("lat="+encodeURIComponent(lat)+"&lng="+encodeURIComponent(lng));
  });
}
sendLocation();
setInterval(sendLocation, 5000);
</script>

</body>
</html>
