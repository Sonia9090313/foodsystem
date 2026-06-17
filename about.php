
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>About Us — Food Ordering System</title>

  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root {
      --brand: #e67e22;
      --brand-dark: #cf711f;
    }
    body { font-family: "Segoe UI", Roboto, Arial, sans-serif; background:#f7f8fa; color:#333; }

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

    /* Hero Section with Video */
    .hero {
      position: relative;
      min-height: 56vh;
      display:flex;
      align-items:center;
      justify-content:center;
      text-align:center;
      overflow:hidden;
      color:#fff;
    }
    .hero video {
      position: absolute;
      top: 0; left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: 0;
    }
    .hero::after {
      content: "";
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.35);
      z-index: 0;
    }
    .hero .content {
      position: relative;
      z-index: 1;
      padding: 20px;
    }
    .hero h1 { color: var(--brand); font-size:2.6rem; font-weight:800; }
    .hero p { color:#fff; font-size:1.05rem; }

    /* Sections */
    .section { padding:60px 0; }
    .section .lead { font-size:1.05rem; color:#555; }

    /* Feature boxes */
    .feature {
      border-radius:12px;
      background:#fff;
      padding:22px;
      box-shadow:0 6px 18px rgba(0,0,0,0.06);
      height:100%;
    }
    .feature .bi { font-size:28px; color:var(--brand); }

    /* Team */
    .team-card { border-radius:12px; overflow:hidden; background:#fff; box-shadow:0 6px 20px rgba(0,0,0,0.06); }
    .team-card img { width:100%; height:220px; object-fit:cover; }

    .stat {
      background:#fff; border-radius:12px; padding:20px; text-align:center;
      box-shadow:0 6px 18px rgba(0,0,0,0.05);
    }
    .stat h3 { color:var(--brand); font-weight:700; }

    footer { 
      background:var(--brand);
      color:#fff; padding:30px 0; 
    }
    footer a { color: #fff; text-decoration:none; }

    @media (max-width:576px) {
      .hero h1 { font-size:1.8rem; }
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
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="reviews.php">Reviews</a></li>
        <li class="nav-item"><a class="nav-link" href="add_reviews.php">Write Review</a></li>
      </ul>
    </div>
  </div>
</nav>


<header class="hero">
  <video autoplay muted loop playsinline>
    <source src="https://media.istockphoto.com/id/1149914102/video/professional-chef-stirs-and-flips-beef-strips-in-a-pan-over-a-flaming-stove-in-a-commercial.mp4?s=mp4-640x640-is&k=20&c=2KK68wNrk9kdkMCDPgKHHfj1EFOjtGaVuPo2QrqeYmg=" type="video/mp4">
  </video>
  <div class="container text-center content">
    <h1>We deliver happiness — one meal at a time</h1>
    <p class="mt-3">Order from your favourite restaurants, track the rider live and get food delivered fast and fresh.</p>
  </div>
</header>

<!-- Mission Section -->
<section class="section bg-white">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-lg-6">
        <h2 style="color:var(--brand)">Our Mission</h2>
        <p class="lead">To make ordering food simple, transparent and fast — for customers, restaurants and riders. We aim to connect neighborhoods to delicious meals and empower local restaurants with tech-driven tools.</p>
        <ul class="list-unstyled mt-3">
          <li class="mb-2"><i class="bi bi-check2-circle me-2" style="color:var(--brand)"></i> Multi-restaurant marketplace</li>
          <li class="mb-2"><i class="bi bi-check2-circle me-2" style="color:var(--brand)"></i> Real-time rider GPS tracking</li>
          <li class="mb-2"><i class="bi bi-check2-circle me-2" style="color:var(--brand)"></i> Easy menu management & analytics for restaurants</li>
        </ul>
      </div>
      <div class="col-lg-6">
        <img src="a.png" width="90%" height="100%">
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="feature p-4 text-center">
          <div class="mb-3"><i class="bi bi-geo-alt"></i></div>
          <h5>GPS Tracking</h5>
          <p class="mb-0">Live rider location updates so customers can follow deliveries on the map.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature p-4 text-center">
          <div class="mb-3"><i class="bi bi-card-list"></i></div>
          <h5>Menu Management</h5>
          <p class="mb-0">Restaurants can add, edit and upload item images quickly from their panel.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature p-4 text-center">
          <div class="mb-3"><i class="bi bi-bar-chart-line"></i></div>
          <h5>Analytics & Reports</h5>
          <p class="mb-0">Daily/monthly sales, commission reports and top-performers for admin & restaurants.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TEAM -->
<section class="section bg-white">
  <div class="container">
    <div class="row align-items-center mb-4">
      <div class="col-md-8">
        <h3 style="color:var(--brand)">Our Team</h3>
        <p class="lead">A small, dedicated team building a reliable food delivery experience.</p>
      </div>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="team-card">
          <img src="d.jpg" alt="Team member">
          <div class="p-3">
            <h5 class="mb-1">Ayesha Khan</h5>
            <small class="text-muted">Co-founder & Product</small>
            <p class="mt-2 mb-0">Leads product design and restaurant partnerships.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="team-card">
          <img src="b.jpg" alt="Team member">
          <div class="p-3">
            <h5 class="mb-1">Bilal Ahmed</h5>
            <small class="text-muted">Tech Lead</small>
            <p class="mt-2 mb-0">Backend and maps integration specialist.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="team-card">
          <img src="c.jpg" alt="Team member">
          <div class="p-3">
            <h5 class="mb-1">Sara Malik</h5>
            <small class="text-muted">Operations</small>
            <p class="mt-2 mb-0">Runs onboarding and support for restaurants & riders.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- STATS -->
<section class="section">
  <div class="container">
    <div class="row g-4 text-center">
      <div class="col-md-3">
        <div class="stat">
          <h3>500+</h3>
          <p>Orders / Day (example)</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat">
          <h3>120+</h3>
          <p>Restaurants</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat">
          <h3>300+</h3>
          <p>Active Riders</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat">
          <h3>10k+</</h3>
          <p>Happy Customers</p>
        </div>
      </div>
    </div>
  </div>
</section>


<footer>
  <div class="container text-center">
    <p class="mb-1"><strong>Food Ordering System</strong> — Connect. Order. Enjoy.</p>
    <p class="mb-0">Support: <a href="mailto:support@example.com">support@example.com</a> | Phone: +92 300 0000000</p>
    <div class="social-icons">
    <a href="#"><i class="bi bi-facebook"></i></a>
    <a href="#"><i class="bi bi-twitter"></i></a>
    <a href="#"><i class="bi bi-instagram"></i></a>
    <a href="#"><i class="bi bi-linkedin"></i></a>
  </div>
  </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
