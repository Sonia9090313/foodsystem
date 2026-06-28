<?php
session_start();
include 'db.php';

if(!isset($_SESSION['restaurant_id'])){
    header("Location: login.php");
    exit();
}

$restaurant_id = $_SESSION['restaurant_id'];
$restaurant_name = $_SESSION['restaurant_name'];


if(isset($_GET['action']) && isset($_GET['order_id'])){
    $order_id = (int)$_GET['order_id'];
    if($_GET['action']=='accept'){
        mysqli_query($con,"UPDATE orders SET status='accepted', updated_at=NOW() WHERE id='$order_id'");
    } elseif($_GET['action']=='reject'){
        mysqli_query($con,"UPDATE orders SET status='rejected', updated_at=NOW() WHERE id='$order_id'");
    }
    header("Location: orders.php");
    exit();
}

// Update Status
if(isset($_POST['update_status'])){
    $order_id = (int)$_POST['order_id'];
    $new_status = mysqli_real_escape_string($con,$_POST['status']);
    mysqli_query($con,"UPDATE orders SET status='$new_status', updated_at=NOW() WHERE id='$order_id'");
    header("Location: orders.php");
    exit();
}

// Assign Rider
if(isset($_POST['assign_rider'])){
    $order_id = (int)$_POST['order_id'];
    if(isset($_POST['rider_id']) && !empty($_POST['rider_id'])){
        $rider_id = (int)$_POST['rider_id'];
        mysqli_query($con,"UPDATE orders SET rider_id='$rider_id' WHERE id='$order_id'");
        header("Location: orders.php");
        exit();
    } else {
        $error_assign = "No rider selected!";
    }
}

// Fetch orders
$sql_orders = "SELECT o.*, u.name AS customer_name, u.email AS customer_email, r.id AS rider_id, r.name AS rider_name, r.latitude, r.longitude
               FROM orders o
               JOIN users u ON o.customer_id = u.id
               LEFT JOIN riders r ON o.rider_id = r.id
               JOIN order_items oi ON o.id = oi.order_id
               JOIN menu_items m ON oi.menu_id = m.id
               WHERE m.restaurant_id='$restaurant_id'
               GROUP BY o.id
               ORDER BY o.created_at DESC";
$res_orders = mysqli_query($con, $sql_orders);

// Fetch active riders
$res_riders = mysqli_query($con,"SELECT * FROM riders WHERE status='active'");
$riders = [];
if($res_riders){
    while($r = mysqli_fetch_assoc($res_riders)){
        $riders[] = $r;
    }
}

// Fetch restaurant profile for sidebar logo
$profile = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM restaurants WHERE id='$restaurant_id'"));
$logo = $profile['logo'] ?? 'default_logo.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Restaurant Orders</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY"></script>
<style>
body {font-family:'Segoe UI', sans-serif; background:#f4f6f9;}
.sidebar {
  width: 240px;
  background: #e67e22;
  min-height: 100vh;
  position: fixed;
  color: #fff;
  padding-top: 20px;
}
.sidebar a {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 20px;
  color: #fff;
  text-decoration: none;
  margin-bottom: 5px;
  border-radius: 8px;
  transition: .3s;
}
.sidebar a:hover {background:#cf711f;}
.sidebar i {font-size:1.2rem;}
.profile-logo {
  width: 80px; height: 80px; border-radius: 50%;
  object-fit: cover; margin-bottom: 10px; border: 2px solid #fff;
}
.main-content {margin-left: 260px; padding: 20px;}
.card {border-radius:10px; margin-bottom:20px;}
.table th {background:#e67e22; color:#fff;}
footer {
  text-align:center;
  padding:15px;
  background:#fff;
  box-shadow:0 -2px 10px rgba(0,0,0,0.1);
  margin-top:30px;
}
</style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
<h3 class="mb-4">🛒 Orders</h3>

<?php if(isset($error_assign)): ?>
<div class="alert alert-danger"><?= $error_assign ?></div>
<?php endif; ?>

<?php
if($res_orders && mysqli_num_rows($res_orders) > 0){
    while($order = mysqli_fetch_assoc($res_orders)){
        echo "<div class='card p-3 shadow-sm mb-3'>
                <h5>Order #".$order['id']." | Status: ".$order['status']."</h5>
                <p><strong>Customer:</strong> ".$order['customer_name']." | ".$order['customer_email']."</p>
                <p><strong>Placed on:</strong> ".$order['created_at']." | <strong>Payment:</strong> ".$order['payment_method']."</p>";
        
        if($order['status']=='pending'){
            echo "<div class='mb-2'>
                    <a href='?action=accept&order_id=".$order['id']."' class='btn btn-success btn-sm me-1'>Accept</a>
                    <a href='?action=reject&order_id=".$order['id']."' class='btn btn-danger btn-sm'>Reject</a>
                  </div>";
        }

        $order_id = $order['id'];
        $sql_items = "SELECT m.name AS item_name, m.image, oi.quantity, oi.price
                      FROM order_items oi
                      JOIN menu_items m ON oi.menu_id = m.id
                      WHERE oi.order_id='$order_id' AND m.restaurant_id='$restaurant_id'";
        $res_items = mysqli_query($con, $sql_items);

        if($res_items && mysqli_num_rows($res_items) > 0){
            echo "<table class='table table-bordered'>
                    <tr><th>Item</th><th>Image</th><th>Price</th><th>Qty</th><th>Total</th></tr>";
            $order_total = 0;
            while($item = mysqli_fetch_assoc($res_items)){
                $subtotal = $item['price'] * $item['quantity'];
                $order_total += $subtotal;
                echo "<tr>
                        <td>".$item['item_name']."</td>
                        <td>";
                if(!empty($item['image'])){
                    echo "<img src='uploads/".$item['image']."' style='width:50px; height:50px; object-fit:cover;'>";
                }
                echo "</td>
                        <td>$".$item['price']."</td>
                        <td>".$item['quantity']."</td>
                        <td>$".$subtotal."</td>
                      </tr>";
            }
            echo "<tr>
                    <td colspan='4' class='text-end fw-bold'>Order Total</td>
                    <td class='fw-bold'>$".$order_total."</td>
                  </tr></table>";
        }

        echo "<form method='post' class='mb-2 d-flex align-items-center gap-2'>
                <input type='hidden' name='order_id' value='".$order['id']."'>
                <select name='status' class='form-select w-auto'>
                    <option ".($order['status']=='pending'?'selected':'').">pending</option>
                    <option ".($order['status']=='preparing'?'selected':'').">preparing</option>
                    <option ".($order['status']=='ready'?'selected':'').">ready</option>
                    <option ".($order['status']=='out for delivery'?'selected':'').">out for delivery</option>
                    <option ".($order['status']=='delivered'?'selected':'').">delivered</option>
                </select>
                <button type='submit' name='update_status' class='btn btn-warning btn-sm'>Update Status</button>
              </form>";

        if(count($riders) > 0){
            echo "<form method='post' class='mb-2 d-flex align-items-center gap-2'>
                    <input type='hidden' name='order_id' value='".$order['id']."'>
                    <select name='rider_id' class='form-select w-auto'>";
            foreach($riders as $r){
                $selected = ($order['rider_id']==$r['id'])?'selected':'';
                echo "<option value='".$r['id']."' $selected>".$r['name']."</option>";
            }
            echo "</select>
                    <button type='submit' name='assign_rider' class='btn btn-primary btn-sm'>Assign Rider</button>
                  </form>";
        } else {
            echo "<p class='text-danger'>No active riders available to assign!</p>";
        }

        if(!empty($order['rider_id'])){
            if(!empty($order['latitude']) && !empty($order['longitude'])){
                echo "<button class='btn btn-info btn-sm mb-2' onclick='trackRider(".$order['latitude'].",".$order['longitude'].")'>Track Rider</button>";
            } else {
                echo "<p class='text-warning'>Rider assigned but location not updated yet!</p>";
            }
        } else {
            echo "<p class='text-danger'>No rider assigned yet!</p>";
        }

        echo "</div>";
    }
} else {
    echo "<p>No orders found.</p>";
}
?>

<footer>
  &copy; <?php echo date('Y'); ?> Restaurant Panel | All Rights Reserved
</footer>
</div>

<
<div class="modal fade" id="riderMapModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Rider Location</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="map" style="height:400px; width:100%;"></div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function trackRider(lat,lng){
    var mapModal = new bootstrap.Modal(document.getElementById('riderMapModal'));
    mapModal.show();
    var map = new google.maps.Map(document.getElementById('map'), {
        center:{lat:parseFloat(lat), lng:parseFloat(lng)},
        zoom:15
    });
    new google.maps.Marker({position:{lat:parseFloat(lat), lng:parseFloat(lng)}, map:map, title:"Rider Location"});
}
</script>
</body>
</html>