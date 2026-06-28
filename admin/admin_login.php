<?php
session_start();
include 'db.php';

$error = "";

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Admin check
    $sql = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = mysqli_query($con, $sql);

    if(mysqli_num_rows($result) == 1){
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "❌ Invalid Username or Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login | FoodSystem</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #ff7e5f, #feb47b);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .login-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      padding: 30px;
      width: 100%;
      max-width: 400px;
      animation: fadeIn 0.8s ease-in-out;
    }
    .login-card h3 {
      font-weight: bold;
      color: #333;
    }
    .btn-custom {
      background: #ff7e5f;
      border: none;
      font-weight: bold;
    }
    .btn-custom:hover {
      background: #eb6b4d;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="login-card">
    <h3 class="text-center mb-4">Admin Login</h3>
    <?php if($error): ?>
      <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">👤 Username</label>
        <input type="text" name="username" class="form-control" placeholder="Enter username" required>
      </div>
      <div class="mb-3">
        <label class="form-label">🔑 Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
      </div>
      <button type="submit" name="login" class="btn btn-custom w-100 py-2">Login</button>
    </form>
    <p class="text-center text-muted mt-3 mb-0" style="font-size: 14px;">
      © <?= date("Y") ?> FoodSystem | Admin Panel
    </p>
  </div>
</body>
</html>
