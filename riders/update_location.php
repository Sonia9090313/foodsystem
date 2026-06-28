<?php
session_start();
include 'db.php';

if (!isset($_SESSION['rider_id'])) {
    exit("Not logged in");
}

$rider_id = (int) $_SESSION['rider_id'];
$lat = $_POST['lat'] ?? null;
$lng = $_POST['lng'] ?? null;

if ($lat && $lng) {
    $sql = "INSERT INTO rider_location (rider_id, lat, lng, updated_at)
            VALUES ('$rider_id','$lat','$lng',NOW())
            ON DUPLICATE KEY UPDATE lat='$lat', lng='$lng', updated_at=NOW()";
    mysqli_query($con, $sql);
}
?>