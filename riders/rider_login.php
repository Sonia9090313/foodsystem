<?php
session_start();
include 'db.php';
$error = '';

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $res = mysqli_query($con, "SELECT * FROM riders WHERE email='$email' AND password='$password' AND status='active'");
    if(mysqli_num_rows($res) == 1){
        $row = mysqli_fetch_assoc($res);
        $_SESSION['rider_id'] = $row['id'];
        $_SESSION['rider_name'] = $row['name'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Rider Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#f4f6f9; }
        .card { max-width:400px; margin:80px auto; border-radius:12px; box-shadow:0 5px 20px rgba(0,0,0,0.1); padding:25px; }
        h3 { color:#e67e22; text-align:center; margin-bottom:25px; }
        .btn-warning { background:#e67e22; border:none; }
        .btn-warning:hover { background:#cf711f; }
    </style>
</head>
<body>
<div class="card">
    <h3>Rider Login</h3>
    <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="post">
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="mb-3">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
        </div>
        <button type="submit" name="login" class="btn btn-warning w-100">Login</button>
        <p class="mt-3 text-center">Don't have an account? <a href="rider_register.php">Register here</a></p>
    </form>
</div>
</body>
</html>