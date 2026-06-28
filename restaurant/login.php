<?php
session_start();
include 'db.php'; 

$email_input = "";
$password_input = "";
$error = "";

// Form submit
if(isset($_POST['login'])){
    $email_input = trim($_POST['email']);
    $password_input = trim($_POST['password']);

    // Check restaurants table
    $sql = "SELECT * FROM restaurants WHERE email='$email_input' LIMIT 1";
    $result = mysqli_query($con, $sql);

    if($result && mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);

        if($row['status'] != 'active'){
            $error = "Your account is not active yet!";
        } else {
            // Check password (hashed or plain)
            if(password_get_info($row['password'])['algo'] > 0){
                if(password_verify($password_input, $row['password'])){
                    $_SESSION['restaurant_id'] = $row['id'];
                    $_SESSION['restaurant_name'] = $row['name'];
                    $_SESSION['restaurant_email'] = $row['email'];
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Incorrect password!";
                }
            } else {
                if($password_input === $row['password']){
                    $_SESSION['restaurant_id'] = $row['id'];
                    $_SESSION['restaurant_name'] = $row['name'];
                    $_SESSION['restaurant_email'] = $row['email'];
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Incorrect password!";
                }
            }
        }
    } else {
        $error = "Restaurant not found with this email!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Restaurant Login</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
body { background:#f0f8ff; }
.card { max-width:400px; margin:100px auto; padding:30px; border-radius:15px; box-shadow:0 5px 20px rgba(0,0,0,0.2);}
.btn-primary { background:#e67e22; border:#e67e22; }
.btn-primary:hover { background:#cf711f; border:#cf711f; }
</style>
</head>
<body>
<div class="card">
    <h3 class="text-center mb-4" style="color:#e67e22;">Restaurant Login</h3>
    <?php if($error != ""){ ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php } ?>
    <form method="post">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($email_input); ?>">
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required value="<?php echo htmlspecialchars($password_input); ?>">
        </div>
        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
        <p class="text-center mt-3">
            Don't have an account? <a href="register.php">Register</a>
        </p>
    </form>
</div>
</body>
</html>