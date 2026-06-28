<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Food Ordering System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin:0; padding:0;
    background:#f8f9fa;
}


.navbar {
    background:#e67e22;
}
.navbar .nav-link {
    color:#fff !important;
    font-weight:500;
    margin-right:15px;
}
.navbar .nav-link:hover {
    color:#ffd700 !important;
}

.hero {

/*
    background: url("https://images.unsplash.com/photo-1600891963930-9609fcd1a36f?auto=format&fit=crop&w=1950&q=80") 
                no-repeat center center/cover;*/

background: url('https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=1950&q=80') center/cover no-repeat;;


    height: 75vh;
    display:flex;
    justify-content:center;
    align-items:center;
    color:#fff;
    text-align:center;
    position:relative;
}
.hero::after {
    content:"";
    position:absolute; top:0;
     left:0;
      right:0;
       bottom:0;
    background:rgba(0,0,0,0.55);
}
.hero-content {
    position:relative;
    z-index:2;
}
.hero h1 {
    font-size:56px;
    font-weight:700;
    color:#ffd700;
}
.hero p {
    font-size:20px;
    margin-top:10px;
}


.cards-section {
    padding:70px 20px;
    text-align:center;
}
.cards-section h2 {
    color:#e67e22;
    margin-bottom:40px;
}
.role-card {
    border:none;
    border-radius:15px;
    box-shadow:0 6px 18px rgba(0,0,0,0.1);
    transition:0.3s;
}
.role-card:hover {
    transform:translateY(-8px);
    box-shadow:0 10px 25px rgba(0,0,0,0.15);
}
.role-card .card-body {
    padding:30px;
}
.role-card i {
    font-size:40px;
    color:#e67e22;
    margin-bottom:20px;
}


footer {
    background:#e67e22;
     color:#fff;
    padding:25px 20px; 
    text-align:center;
    font-size:15px;
}
footer .social-icons a {
    color:#fff; 
    font-size:18px; 
    margin:0 8px;
    text-decoration:none;
     transition:0.3s;
}
footer .social-icons a:hover {
  color:#ffd700;
}
</style>
</head>
<body>


<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand text-white fw-bold" href="#">FoodOrdering</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="reviews.php">Reviews</a></li>
        <li class="nav-item"><a class="nav-link" href="add_reviews.php">Write Review</a></li>
        <!-- <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li> -->
      </ul>
    </div>
  </div>
</nav>

<!-- Hero -->
<div class="hero">
  <div class="hero-content" data-aos="fade-up">
    <h1>Welcome to Food Ordering System</h1>
    <p>Order food from multiple restaurants easily and get fast delivery 🚀</p>
  </div>
</div>


<div class="cards-section">
  <h2 data-aos="fade-down">Choose Your Role</h2>
  <div class="container">
    <div class="row g-4">
      <div class="col-md-3" data-aos="zoom-in">
        <div class="card role-card h-100">
          <div class="card-body text-center">
            <i class="bi bi-bag-fill"></i>
            <h5 class="card-title">Customer</h5>
            <p class="card-text">Browse restaurants, order food, and enjoy fast delivery.</p>
            <a href="customer/login.php" class="btn btn-warning">Login</a>
          </div>
        </div>
      </div>
      <div class="col-md-3" data-aos="zoom-in" data-aos-delay="150">
        <div class="card role-card h-100">
          <div class="card-body text-center">
            <i class="bi bi-shop"></i>
            <h5 class="card-title">Restaurant</h5>
            <p class="card-text">Manage menus, accept orders, and grow your business.</p>
            <a href="restaurant/login.php" class="btn btn-warning">Login</a>
          </div>
        </div>
      </div>
      <div class="col-md-3" data-aos="zoom-in" data-aos-delay="300">
        <div class="card role-card h-100">
          <div class="card-body text-center">
            <i class="bi bi-bicycle"></i>
            <h5 class="card-title">Rider</h5>
            <p class="card-text">Deliver food quickly and earn by completing deliveries.</p>
            <a href="riders/rider_login.php" class="btn btn-warning">Login</a>
          </div>
        </div>
      </div>
      <div class="col-md-3" data-aos="zoom-in" data-aos-delay="450">
        <div class="card role-card h-100">
          <div class="card-body text-center">
            <i class="bi bi-gear-fill"></i>
            <h5 class="card-title">Admin</h5>
            <p class="card-text">Control restaurants, riders, and overall platform settings.</p>
            <a href="admin/admin_login.php" class="btn btn-warning">Login</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<footer data-aos="fade-up">
  <p>&copy; <?= date('Y'); ?> Food Ordering System. All Rights Reserved.</p>
  <div class="social-icons">
    <a href="#"><i class="bi bi-facebook"></i></a>
    <a href="#"><i class="bi bi-twitter"></i></a>
    <a href="#"><i class="bi bi-instagram"></i></a>
    <a href="#"><i class="bi bi-linkedin"></i></a>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({ duration:1000, once:true });
  
</script>
</body>
</html>