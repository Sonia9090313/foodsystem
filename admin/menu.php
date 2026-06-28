<?php
session_start();
include 'db.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

// Restaurant ID check
if(!isset($_GET['rid']) || empty($_GET['rid'])){
    header("Location: restaurants.php");
    exit();
}

$rid = (int)$_GET['rid'];

// Fetch restaurant details
$sql_rest = "SELECT * FROM restaurants WHERE id='$rid'";
$res_rest = mysqli_query($con, $sql_rest);
$restaurant = mysqli_fetch_assoc($res_rest);

if(!$restaurant){
    header("Location: restaurants.php");
    exit();
}

// Fetch menu items
$sql_menu = "SELECT * FROM menu_items WHERE restaurant_id='$rid' ORDER BY id DESC";
$res_menu = mysqli_query($con, $sql_menu);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Menu - <?= htmlspecialchars($restaurant['name']); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {background:#f9f9f9; font-family:'Segoe UI', sans-serif;}
.sidebar {
    width:220px; background:#FF7F50; min-height:100vh; position:fixed; color:#fff; padding-top:20px; display:flex; flex-direction:column;
}
.sidebar h4 {text-align:center; margin-bottom:20px;}
.sidebar a {display:flex; align-items:center; gap:10px; padding:12px 20px; color:#fff; text-decoration:none; margin-bottom:5px; border-radius:8px;}
.sidebar a:hover {background:#e26f47;}
.main-content {margin-left:240px; padding:20px;}
h3 {color:#FF7F50; margin-bottom:20px;}
.table th {background:#FF7F50; color:#fff;}
.card {border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1); margin-bottom:20px;}
.btn-orange, .btn-orange:hover {background:#FF7F50; color:#fff; border:none;}
</style>
</head>
<body>

<!-- Sidebar (dashboard style) -->
<?php include "navbar.php" ?>

<!-- Main Content -->
<div class="main-content">
    <h3>📋 Menu - <?= htmlspecialchars($restaurant['name']); ?></h3>
    <a href="restaurants.php" class="btn btn-secondary btn-sm mb-3"><i class="bi bi-arrow-left"></i> Back</a>

    <div class="card p-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item Name</th>
                    <th>Price</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php if(mysqli_num_rows($res_menu) > 0): ?>
                <?php while($item = mysqli_fetch_assoc($res_menu)): ?>
                <tr>
                    <td><?= $item['id']; ?></td>
                    <td><?= htmlspecialchars($item['name']); ?></td>
                    <td>$<?= $item['price']; ?></td>
                    <td>
                        <span class="badge bg-<?= ($item['status']=='available'?'success':'secondary'); ?>">
                            <?= ucfirst($item['status']); ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center">No menu items found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>