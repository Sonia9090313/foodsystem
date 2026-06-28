<div class="sidebar">
  <h4>FoodSystem</h4>
  <p class="text-center">
    👋 Welcome,<br>
    <strong><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''; ?></strong>
  </p>
  <hr style="border-color:rgba(255,255,255,0.3);">
  <a href="dashboard.php">🏠 Dashboard</a>
  <a href="restaurant_list.php">🍴 Browse Restaurants</a>
  <a href="cart.php">🛒 My Cart</a>
  <a href="my_order.php">📦 My Orders</a>
  <a href="profile.php">👤 My Profile</a>
  <a href="logout.php" style="color:#fff;">🚪 Logout</a>
</div>


<style>
  body {
  background:#f4f6f9;
  font-family:'Segoe UI', sans-serif;
  margin:0;
  padding:0;
}

/* SIDEBAR SAME AS YOURS */
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


.sidebar a.active {
  background:none !important;
}


.sidebar a:hover {
  background:#e67e22;
}

/* MAIN CONTENT */
.main-content {
  margin-left:250px;
  padding:30px;
}

/* FOOTER — FIXED, NO GAP */
.footer {
  background:#e67e22;
  color:white;
  text-align:center;
  padding:12px;
  position:fixed;
  bottom:0;
  left:230px;
  width:calc(100% - 230px);
}
</style>