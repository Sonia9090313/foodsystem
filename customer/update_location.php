<?php
session_start();
if(!isset($_SESSION['rider_id'])){
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit();
}

include '../db.php';

$order_id = intval($_POST['order_id']);
$lat = floatval($_POST['lat']);
$lng = floatval($_POST['lng']);

$sql = "UPDATE orders SET delivery_lat='$lat', delivery_lng='$lng', rider_id='".$_SESSION['rider_id']."' WHERE id='$order_id'";
if(mysqli_query($con, $sql)){
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => mysqli_error($con)]);
}
?>