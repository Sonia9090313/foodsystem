<?php
session_start();
if(!isset($_SESSION['userid'])){
    header("Location: login.php");
    exit();
}

include 'db.php';

if(!isset($_GET['order_id'])){
    die("Order ID missing.");
}
$order_id = intval($_GET['order_id']);

// Fetch order info
$sql_order = "SELECT * FROM orders WHERE id='$order_id' AND customer_id='".$_SESSION['userid']."'";
$res_order = mysqli_query($con, $sql_order);
if(!$res_order || mysqli_num_rows($res_order) == 0){
    die("Order not found.");
}
$order = mysqli_fetch_assoc($res_order);

// Check if rider assigned
$rider_assigned = !is_null($order['rider_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Rider Tracking - Order #<?php echo $order['id']; ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY"></script>
<style>
body {background:#f9f9f9;}
h3 {color:#e67e22;}
#map {width:100%; height:400px; border-radius:10px; margin-top:15px;}
.btn-orange {background:#e67e22; color:#fff;}
.btn-orange:hover {background:#cf711f;}
</style>
</head>
<body>
<div class="container mt-4">
<h3>🚴 Rider Tracking - Order #<?php echo $order['id']; ?></h3>
<a href="my_order.php" class="btn btn-secondary btn-sm mb-3">⬅ Back to My Orders</a>

<?php if($rider_assigned): ?>
    <div id="map"></div>
    <script>
    function initMap() {
        const riderLocation = {lat: <?php echo $order['delivery_lat'] ?? 0; ?>, lng: <?php echo $order['delivery_lng'] ?? 0; ?>};
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: riderLocation
        });
        const marker = new google.maps.Marker({
            position: riderLocation,
            map: map,
            title: "Rider Location"
        });
    }
    window.onload = initMap;
    </script>
<?php else: ?>
    <p>The rider has not been assigned yet. Please wait for your order to be picked up.</p>
<?php endif; ?>

</div>
</body>
</html>