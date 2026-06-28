<?php
session_start();
include 'db.php';
$error = '';
$success = '';

if(isset($_POST['register'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // check existing email
    $check = mysqli_query($con, "SELECT * FROM riders WHERE email='$email'");
    if(mysqli_num_rows($check) > 0){
        $error = "Email already registered!";
    } else {
        mysqli_query($con, "INSERT INTO riders (name,email,phone,password,status) VALUES ('$name','$email','$phone','$password','active')");
        $success = "Registration successful! You can now login.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Rider Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#f4f6f9; }
        .card { max-width:500px; margin:50px auto; border-radius:12px; box-shadow:0 5px 20px rgba(0,0,0,0.1); padding:30px; }
        h3 { color:#e67e22; text-align:center; margin-bottom:25px; }
        .btn-warning { background:#e67e22; border:none; }
        .btn-warning:hover { background:#cf711f; }
    </style>
</head>
<body>
<div class="card">
    <h3>Rider Registration</h3>
    <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
    <form method="post">
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
        </div>
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" placeholder="Enter email" required>
        </div>
        <div class="mb-3">
            <label>Phone:</label>
            <input type="text" name="phone" class="form-control" placeholder="Enter phone number" required>
        </div>
        <div class="mb-3">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" placeholder="Create password" required>
        </div>
        <button type="submit" name="register" class="btn btn-warning w-100">Register</button>
        <p class="mt-3 text-center">Already have an account? <a href="rider_login.php">Login here</a></p>
    </form>
</div>
</body>
</html>