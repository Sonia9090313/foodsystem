<?php include "db.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reviews</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f8f9fa; 
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
.footer{
  background: #e67e22!important;
}

    .navbar-brand, .nav-link, .footer p { color:#fff !important; }
    .footer { padding:15px; 
      text-align:center;
       margin-top:50px; 
     }
    .review-box { 
      background:#fff; 
      padding:15px;
       border-radius:8px;
        margin-bottom:15px; 
        box-shadow:0 2px 5px rgba(0,0,0,0.1);
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
        <!-- <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li> -->
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <h2 class="text-center mb-4">Customer Reviews</h2>

  <?php
  if (isset($_GET['success'])) {
      echo "<div class='alert alert-success'> Review submitted successfully!</div>";
  }

  $result = $con->query("SELECT * FROM reviews ORDER BY created_at DESC");
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          echo "<div class='review-box'>";
          echo "<h5>".htmlspecialchars($row['name'])."</h5>";
          echo "<p>⭐ ".str_repeat("⭐", $row['rating'])." (".$row['rating']."/5)</p>";
          echo "<p>".htmlspecialchars($row['review'])."</p>";
          echo "<small class='text-muted'>Posted on ".$row['created_at']."</small>";
          echo "</div>";
      }
  } else {
      echo "<p>No reviews yet. Be the first one!</p>";
  }
  ?>
</div>

<div class="footer">
  <p>&copy; <?= date("Y") ?> Food Delivery. All Rights Reserved.</p>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>