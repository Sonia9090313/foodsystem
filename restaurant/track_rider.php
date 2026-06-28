<?php
session_start();
include 'db.php';

if(!isset($_GET['order_id'])){
    die("Invalid Request");
}

$order_id = $_GET['order_id'];
$order = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM orders WHERE id='$order_id'"));

if(!$order['rider_id']){
    die("No rider assigned for this order!");
}

$rider_id = $order['rider_id'];
$rider = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM riders WHERE id='$rider_id'"));

if(!$rider){
    die("Rider not found!");
}

// Check if location exists
if(!$rider['latitude'] || !$rider['longitude']){
    die("Rider location not available yet!");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Track Rider</title>
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY"></script>
<style>
#map { width: 100%; height: 500px; }
</style>
</head>
<body>
<h3>Rider: <?php echo $rider['name']; ?></h3>
<div id="map"></div>
<script>
var riderLocation = {lat: <?php echo $rider['longitude']; ?>, lng: <?php echo $rider['longitude']; ?>};

var map = new google.maps.Map(document.getElementById('map'), {
    center: riderLocation,
    zoom: 15
});

var marker = new google.maps.Marker({
    position: riderLocation,
    map: map,
    title: 'Rider Location'
});
</script>
</body>
</html>