<?php
session_start();
if(!isset($_SESSION['userid'])){
    header("Location: login.php");
    exit();
}

include 'db.php'; // Database connection

if(isset($_POST['update_profile'])){
    $userid = $_SESSION['userid'];
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $password = $_POST['password'];

    if(!empty($password)){
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET name='$name', email='$email', phone='$phone', password='$hashed_password' WHERE id='$userid'";
    } else {
        $sql = "UPDATE users SET name='$name', email='$email', phone='$phone' WHERE id='$userid'";
    }

    if(mysqli_query($con, $sql)){
        $_SESSION['message'] = "Profile updated successfully!";
    } else {
        $_SESSION['message'] = "Error updating profile: " . mysqli_error($con);
    }
}

header("Location: profile.php");
exit();