<?php
include 'db.php'; // Database connection

$message = "";

if(isset($_POST['register'])){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];

    // Check if email or phone already exists
    $check = mysqli_query($con, "SELECT * FROM users WHERE email='$email' OR phone='$phone'");
    if(mysqli_num_rows($check) > 0){
        $message = "<span style='color:red;'>Email or Phone already registered!</span>";
    } else {
        $sql = "INSERT INTO users (name, email, phone, password, role) 
                VALUES ('$name', '$email', '$phone', '$password', 'customer')";
        if(mysqli_query($con, $sql)){
            $message = "<span style='color:green;'>✅ Registration successful! <a href='login.php'>Login Now</a></span>";
        } else {
            $message = "<span style='color:red;'>❌ Error: " . mysqli_error($con) . "</span>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Customer Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {background: #f7f7f7; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;}
.register-container {max-width:420px; margin:80px auto; background:#fff; padding:30px; border-radius:12px; box-shadow:0px 5px 15px rgba(0,0,0,0.1);}
.register-container h2 {text-align:center; margin-bottom:20px; color:#e67e22;}
.btn-custom {background:#e67e22; color:#fff; font-weight:bold;}
.btn-custom:hover {background:#d35400;}
</style>
</head>
<body>
<div class="register-container">
    <h2>Create Account</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control" placeholder="Enter your phone number" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Create a password" required>
        </div>
        <button type="submit" name="register" class="btn btn-custom w-100">Register</button>
    </form>
    <div class="mt-3 text-center"><?php echo $message; ?></div>
    <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here</a></p>
</div>
</body>
</html>
