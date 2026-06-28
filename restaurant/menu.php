<?php
session_start();
include 'db.php';

if(!isset($_SESSION['restaurant_id'])){
    header("Location: login.php");
    exit();
}

$restaurant_id = $_SESSION['restaurant_id'];
$restaurant_name = $_SESSION['restaurant_name'];

// Add Menu Item
if(isset($_POST['add_item'])){
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $price = floatval($_POST['price']);
    $image_name = "";

    if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){
        $img = $_FILES['image'];
        $ext = pathinfo($img['name'], PATHINFO_EXTENSION);
        $image_name = time()."_".$restaurant_id.".".$ext;
        move_uploaded_file($img['tmp_name'], "uploads/".$image_name);
    }

    mysqli_query($con, "INSERT INTO menu_items (restaurant_id, name, price, image, created_at) 
                        VALUES ('$restaurant_id', '$name', '$price', '$image_name', NOW())");
}

// Update Menu Item
if(isset($_POST['update_item'])){
    $id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $price = floatval($_POST['price']);

    if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){
        $img = $_FILES['image'];
        $ext = pathinfo($img['name'], PATHINFO_EXTENSION);
        $image_name = time()."_".$restaurant_id.".".$ext;
        move_uploaded_file($img['tmp_name'], "uploads/".$image_name);
        mysqli_query($con,"UPDATE menu_items SET name='$name', price='$price', image='$image_name' WHERE id='$id' AND restaurant_id='$restaurant_id'");
    } else {
        mysqli_query($con,"UPDATE menu_items SET name='$name', price='$price' WHERE id='$id' AND restaurant_id='$restaurant_id'");
    }
}

// Delete item
if(isset($_GET['delete_id'])){
    $id = (int)$_GET['delete_id'];
    mysqli_query($con, "DELETE FROM menu_items WHERE id='$id' AND restaurant_id='$restaurant_id'");
}

// Fetch menu items
$sql = "SELECT * FROM menu_items WHERE restaurant_id='$restaurant_id' ORDER BY created_at DESC";
$res = mysqli_query($con, $sql);

// Fetch item for edit
$edit_item = null;
if(isset($_GET['edit_id'])){
    $id = (int)$_GET['edit_id'];
    $res_edit = mysqli_query($con,"SELECT * FROM menu_items WHERE id='$id' AND restaurant_id='$restaurant_id'");
    if($res_edit && mysqli_num_rows($res_edit) > 0){
        $edit_item = mysqli_fetch_assoc($res_edit);
    }
}

// Fetch restaurant logo
$profile = mysqli_fetch_assoc(mysqli_query($con,"SELECT logo FROM restaurants WHERE id='$restaurant_id'"));
$logo = $profile['logo'] ?? 'default_logo.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Menu Management</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6f9;
    margin: 0;
    padding: 0;
}
.sidebar {
    width: 240px;
    background: #e67e22;
    color: #fff;
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    padding-top: 20px;
    text-align: center;
    box-shadow: 2px 0 8px rgba(0,0,0,0.1);
}
.sidebar img.profile-logo {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 10px;
    border: 2px solid #fff;
}
.sidebar h5 {
    margin-bottom: 20px;
    font-weight: 600;
}
.sidebar a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    color: #fff;
    text-decoration: none;
    margin-bottom: 5px;
    border-radius: 8px;
    transition: 0.3s;
}
.sidebar a:hover {
    background: #cf711f;
}
.sidebar i {
    font-size: 1.2rem;
}
.main-content {
    margin-left: 260px;
    padding: 20px;
}
.card {
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.table th {
    background: #e67e22;
    color: #fff;
}
.btn-orange {
    background: #e67e22;
    color: #fff;
}
.btn-orange:hover {
    background: #cf711f;
}
img.menu-img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
}
footer {
    text-align:center;
    padding:15px;
    background:#fff;
    box-shadow:0 -2px 10px rgba(0,0,0,0.1);
    margin-top:30px;
}
</style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<!-- <div class="sidebar">
    <img src="uploads/<?php echo $logo; ?>" class="profile-logo" alt="Logo">
    <h5><?php echo htmlspecialchars($restaurant_name); ?></h5>
    <a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="orders.php"><i class="bi bi-cart"></i> Orders</a>
    <a href="menu.php"><i class="bi bi-list-ul"></i> Menu Items</a>
    <a href="profile.php"><i class="bi bi-person-circle"></i> Profile</a>
    <a href="analytics.php"><i class="bi bi-bar-chart-line"></i> Analytics</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div> -->

<!-- Main Content -->
<div class="main-content">
<h3>🍔 Menu Items</h3>

<!-- Add / Edit Item Form -->
<form method="post" enctype="multipart/form-data" class="mb-4">
    <div class="row">
        <input type="hidden" name="id" value="<?= $edit_item['id'] ?? '' ?>">
        <div class="col-md-4">
            <input type="text" name="name" class="form-control" placeholder="Item Name" required value="<?= $edit_item['name'] ?? '' ?>">
        </div>
        <div class="col-md-3">
            <input type="number" step="0.01" name="price" class="form-control" placeholder="Price" required value="<?= $edit_item['price'] ?? '' ?>">
        </div>
        <div class="col-md-3">
            <input type="file" name="image" class="form-control">
            <?php if(isset($edit_item['image']) && $edit_item['image'] != ""): ?>
                <img src="uploads/<?= $edit_item['image'] ?>" class="menu-img mt-2">
            <?php endif; ?>
        </div>
        <div class="col-md-2">
            <button type="submit" name="<?= $edit_item ? 'update_item' : 'add_item' ?>" class="btn btn-orange w-100">
                <?= $edit_item ? 'Update' : 'Add' ?>
            </button>
        </div>
    </div>
</form>

<!-- Menu Table -->
<?php if($res && mysqli_num_rows($res) > 0): ?>
<table class="table table-bordered align-middle">
    <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php while($row = mysqli_fetch_assoc($res)): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td>
                <?php if($row['image'] != ""): ?>
                    <img src="uploads/<?= $row['image'] ?>" class="menu-img">
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td>$<?= $row['price'] ?></td>
            <td><?= $row['created_at'] ?></td>
            <td>
                <a href="menu.php?edit_id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                <a href="menu.php?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<?php else: ?>
<p>No menu items found.</p>
<?php endif; ?>

</div>

</body>
</html>