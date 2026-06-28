<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

$username = $_SESSION['username'];

// Get Restaurant Info
if(!isset($_GET['rid'])){
    die("Restaurant ID missing.");
}
$rid = intval($_GET['rid']);

$sql_restaurant = "SELECT * FROM restaurants WHERE id='$rid' AND status='active'";
$res_restaurant = mysqli_query($con, $sql_restaurant);
if(!$res_restaurant || mysqli_num_rows($res_restaurant) == 0){
    die("Restaurant not found.");
}
$restaurant = mysqli_fetch_assoc($res_restaurant);

// Get Menu Items
$sql_menu = "SELECT * FROM menu_items WHERE restaurant_id='$rid' AND status='active'";
$res_menu = mysqli_query($con, $sql_menu);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo htmlspecialchars($restaurant['name']); ?> Menu</title>
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
.menu-card {
    background:#fff;
    border-radius:10px;
    margin-bottom:15px;
    box-shadow:0 2px 5px rgba(0,0,0,0.1);
    display:flex;
    flex-wrap:wrap;
    padding:10px;
}
.menu-card img {
    width:120px;
    height:120px;
    object-fit:cover;
    border-radius:10px;
}
.menu-card .menu-details {
    flex:1;
    padding:10px 15px;
}
.menu-card h5 {
    color:#e67e22;
    font-weight:600;
    font-size:17px;
}
.menu-card p {
    color:#555;
    margin-bottom:8px;
}
.btn-orange {
    background:#e67e22;
    color:#fff;
}
.btn-orange:hover {
    background:#cf711f;
}
</style>
</head>
<body>

<?php include 'sidebar.php'; ?>


<div class="main-content">
    <h3 style="color:#e67e22;">🍽 <?php echo htmlspecialchars($restaurant['name']); ?> Menu</h3>

    <?php
    if($res_menu && mysqli_num_rows($res_menu) > 0){
        while($item = mysqli_fetch_assoc($res_menu)){
    ?>
    <div class="menu-card">
        <img src="../restaurant/uploads/<?php echo $item['image']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
        <div class="menu-details">
            <h5><?php echo htmlspecialchars($item['name']); ?></h5>
            <p><strong>$<?php echo $item['price']; ?></strong></p>
            <form method="post" action="add_to_cart.php">
                <input type="hidden" name="menu_id" value="<?php echo $item['id']; ?>">
                <input type="hidden" name="restaurant_id" value="<?php echo $rid; ?>">
                <input type="number" name="quantity" value="1" min="1" class="form-control mb-2" style="width:100px;">
                <button type="submit" class="btn btn-orange">Add to Cart</button>
            </form>
        </div>
    </div>
    <?php
        }
    } else {
        echo "<p>No menu items found.</p>";
    }
     
    ?>

     <?php include 'footer.php'; ?>
</body>
</html>