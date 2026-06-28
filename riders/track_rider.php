<?php
include 'db.php';
$order_id = (int) ($_GET['order_id'] ?? 0);

// Rider ka ID fetch
$res = mysqli_query($con, "SELECT rider_id FROM orders WHERE id='$order_id'");
$order = mysqli_fetch_assoc($res);
$rider_id = $order['rider_id'] ?? 0;

$lat = $lng = null;
if ($rider_id) {
    $res_loc = mysqli_query($con, "SELECT lat, lng FROM rider_location WHERE rider_id='$rider_id'");
    $loc = mysqli_fetch_assoc($res_loc);
    $lat = $loc['lat'] ?? null;
    $lng = $loc['lng'] ?? null;
}

if (!$lat || !$lng) {
    die("Rider location not available yet.");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Track Rider</title>
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
  <style> #map { height: 100vh; width: 100%; } </style>
</head>
<body>
  <h3>Rider Tracking for Order #<?php echo $order_id; ?></h3>
  <div id="map"></div>
  <script>
    function initMap() {
      var riderPos = {lat: <?php echo $lat; ?>, lng: <?php echo $lng; ?>};
      var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: riderPos
      });
      var marker = new google.maps.Marker({
        position: riderPos,
        map: map,
        title: "Rider Current Location"
      });
    }
    initMap();
  </script>
</body>
</html>