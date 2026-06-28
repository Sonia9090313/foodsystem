<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == "approve") {
        mysqli_query($con, "UPDATE users SET status='approved' WHERE id='$id'");
    } elseif ($action == "reject") {
        mysqli_query($con, "UPDATE users SET status='rejected' WHERE id='$id'");
    } elseif ($action == "delete") {
        mysqli_query($con, "DELETE FROM users WHERE id='$id'");
    }
}

header("Location: users.php");
exit();
?>