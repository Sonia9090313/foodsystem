<?php
session_start();
include 'db.php';
$message = "";

if(isset($_POST['register'])){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $check = mysqli_query($con, "SELECT * FROM restaurants WHERE email='$email'");
    if(mysqli_num_rows($check) > 0){
        $message = "<div class='alert alert-danger'>Email already registered!</div>";
    } else {
        $sql = "INSERT INTO restaurants (name,email,phone,password,status) VALUES ('$name','$email','$phone','$password','active')";
        if(mysqli_query($con,$sql)){
            // Auto login after registration
            $restaurant_id = mysqli_insert_id($con);
            $_SESSION['restaurant_id'] = $restaurant_id;
            $_SESSION['restaurant_name'] = $name;
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "<div class='alert alert-danger'>Error: ".mysqli_error($con)."</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Restaurant Registration</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {background: #f0f2f5; font-family: 'Segoe UI', sans-serif;}
.card {border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);}
.btn-custom {background: #e67e22; color: #fff; font-weight: bold;}
.btn-custom:hover {background: #cf711f;}
h3 {color: #e67e22;}
</style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center" style="height:100vh;">
  <div class="card p-4" style="width:400px;">
    <h3 class="text-center mb-4">Restaurant Sign Up</h3>
    <?php echo $message; ?>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Restaurant Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" name="register" class="btn btn-custom w-100">Register & Login</button>
      <p class="text-center mt-3">Already registered? <a href="login.php">Login here</a></p>
    </form>
  </div>
</div>
</body>
</html>