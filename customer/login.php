<?php
session_start();
include 'db.php';  

$error = "";


if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);


    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password' LIMIT 1";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        // Registered user
        $row = mysqli_fetch_assoc($result);
        $_SESSION['userid'] = $row['id'];
        $_SESSION['username'] = $row['name'];
        $_SESSION['email'] = $row['email'];
    } else {
      
        $SESSION['userid'] = "guest".rand(1000,9999); 
        $_SESSION['username'] = "Guest";
        $_SESSION['email'] = $email;
    }

    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body {background-color:#f0f8ff;}
    .card {width:400px; margin:auto; margin-top:80px; padding:20px; border-radius:15px; box-shadow:0 5px 20px rgba(0,0,0,0.2);}
    .btn-primary {background-color:#e67e22; border-color:#e67e22;}
    .btn-primary:hover {background-color:#cf711f; border-color:#cf711f;}
  </style>
</head>
<body>
<div class="card">
    <h3 class="text-center mb-4" style="color:#e67e22;">Customer Login</h3>
    <?php if($error != ""){ ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php } ?>
    <form method="post">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
        <p class="text-center mt-3">
            Don't have an account? <a href="register.php">Register</a>
        </p>
    </form>
</div>
</body>
</html>