<?php include "db.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Write a Review</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
     background:#f8f9fa; 
     font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin:0;
       padding:0;

   }
    .navbar, .footer {
     background-color:#e67e22;
      }
    .navbar-brand, .nav-link, .footer p {
     color:#fff !important;
     margin-right:15px;
     font-weight:500;

      }
    /*.footer { 
      padding:15px;
       text-align:center; 
       margin-top:50px;
        }
*/

        footer {
    background:#e67e22;
     color:#fff;
    padding:25px 20px; 
    text-align:center;
    font-size:15px;
    margin-top: 20px;
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
  <h2 class="text-center mb-4">Write a Review</h2>
  <form method="post" action="submit_review.php">
    <div class="mb-3">
      <label class="form-label">Your Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Rating</label>
      <select name="rating" class="form-select" required>
        <option value="5">⭐⭐⭐⭐⭐</option>
        <option value="4">⭐⭐⭐⭐</option>
        <option value="3">⭐⭐⭐</option>
        <option value="2">⭐⭐</option>
        <option value="1">⭐</option>
        <!-- <option value="0">👎</option> -->
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Your Review</label>
      <textarea name="review" class="form-control" rows="4" required></textarea>
    </div>
    <button type="submit" class="btn btn-warning">Submit Review</button>
  </form>
</div>

<!-- <div class="footer">
  <p>&copy; <?= date("Y") ?> Food Delivery. All Rights Reserved.</p>

</div>
 -->


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




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>