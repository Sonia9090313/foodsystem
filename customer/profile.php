<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Profile</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
body {
  background:#f4f6f9;
  font-family:'Segoe UI', sans-serif;
  margin:0;
  padding:0;
}
.sidebar {
  width:230px;
  background:#e67e22;
  color:#fff;
  position:fixed;
  top:0;
  left:0;
  height:100vh;
  padding:20px 15px;
  box-shadow:2px 0 8px rgba(0,0,0,0.1);
}
.sidebar h4 {
  text-align:center;
  font-weight:bold;
  margin-bottom:30px;
}
.sidebar a {
  display:block;
  color:#fff;
  padding:10px 15px;
  margin-bottom:5px;
  text-decoration:none;
  border-radius:6px;
  transition:0.3s;
}
.sidebar a:hover, .sidebar a.active {
  background:#cf711f;
}
.main-content {
  margin-left:250px;
  padding:30px;
}
.card {
  border-radius:10px;
  box-shadow:0 2px 6px rgba(0,0,0,0.1);
}
footer {
  margin-top:100px;
  background-color:#e67e22;
  color:white;
  text-align:center;
  padding:10px 0;
  font-weight:500;
  font-size:15px;
  letter-spacing:0.5px;
  box-shadow:0 -2px 5px rgba(0,0,0,0.1);
}
footer span {
  color:#fff;
}
</style>
</head>
<body>

<?php include 'sidebar.php'; ?>
<div class="main-content">
  <div class="card p-4">
    <h4 class="mb-3" style="color:#e67e22;">My Profile</h4>
    <form method="post" action="update_profile.php">
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" class="form-control" name="fullname" value="<?php echo htmlspecialchars($username); ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" class="form-control" name="email" value="customer@example.com" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Phone Number</label>
        <input type="text" class="form-control" name="phone" value="0300-0000000" required>
      </div>
      <button type="submit" class="btn btn-warning">Update Profile</button>
    </form>
  </div>

   <?php include 'footer.php'; ?>
</div>

</body>
</html>