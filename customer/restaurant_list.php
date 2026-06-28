<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}
include 'db.php'; 

$username = $_SESSION['username'];
$sql = "SELECT * FROM restaurants WHERE status = 'active' ORDER BY id DESC";
$result = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Browse Restaurants</title>
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
      transition:0.3s;
      box-shadow:0 2px 6px rgba(0,0,0,0.1);
    }
    .card:hover {
      transform:scale(1.02);
      box-shadow:0px 4px 12px rgba(0,0,0,0.15);
    }
    .btn-orange {
      background-color:#e67e22;
      color:#fff;
    }
    .btn-orange:hover {
      background-color:#cf711f;
      color:#fff;
    }
    
  </style>
</head>
<body>

  <?php include 'sidebar.php'; ?>

<!-- <div class="sidebar">
  <h4>FoodSystem</h4>
  <p class="text-center">👋 Welcome,<br><strong><?php echo htmlspecialchars($username); ?></strong></p>
  <hr style="border-color:rgba(255,255,255,0.3);">
  <a href="dashboard.php">🏠 Dashboard</a>
  <a href="restaurant_list.php" class="active">🍴 Browse Restaurants</a>
  <a href="my_order.php">📦 My Orders</a>
  <a href="profile.php">👤 My Profile</a>
  <a href="logout.php" style="color:#fff;">🚪 Logout</a>
</div> -->

<div class="main-content">
  <div class="card p-4">
    <h4 class="mb-3" style="color:#e67e22;">Browse Restaurants</h4>
    <div class="row">
      <?php
      if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
      ?>
        <div class="col-md-4 mb-4">
          <div class="card shadow-sm h-100">
            <img src="uploads/<?php echo htmlspecialchars($row['logo']); ?>" 
                 class="card-img-top" 
                 alt="<?php echo htmlspecialchars($row['name']); ?>" 
                 style="height:150px; object-fit:cover;">
            <div class="card-body text-center">
              <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
              <p class="card-text"><?php echo htmlspecialchars($row['address']); ?></p>
              <a href="menu.php?rid=<?php echo $row['id']; ?>" class="btn btn-orange btn-sm">View Menu</a>
            </div>
          </div>
        </div>
      <?php
          }
      } else {
          echo "<p>No restaurants available right now.</p>";
      }
      ?>
    </div>
  </div>

   <?php include 'footer.php'; ?>
</div>

</body>
</html>