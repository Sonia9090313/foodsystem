<?php
session_start();
include 'db.php';

if(!isset($_SESSION['restaurant_id'])){
    header("Location: login.php");
    exit();
}

$restaurant_id = $_SESSION['restaurant_id'];
$restaurant_name = $_SESSION['restaurant_name'];

// Fetch restaurant data
$res = mysqli_query($con, "SELECT * FROM restaurants WHERE id='$restaurant_id'");
$restaurant = mysqli_fetch_assoc($res);

// Handle profile update
if(isset($_POST['update_profile'])){
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $contact = mysqli_real_escape_string($con, $_POST['contact']);
    $latitude = mysqli_real_escape_string($con, $_POST['latitude']);
    $longitude = mysqli_real_escape_string($con, $_POST['longitude']);
    $logo = $restaurant['logo']; // existing logo

    // Upload new logo
    if(isset($_FILES['logo']) && $_FILES['logo']['error']==0){
        $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $logo_name = time().'_logo.'.$ext;
        $target = 'uploads/'.$logo_name;
        move_uploaded_file($_FILES['logo']['tmp_name'], $target);
        $logo = $logo_name;
    }

    mysqli_query($con, "UPDATE restaurants SET 
        name='$name', 
        address='$address', 
        contact='$contact', 
        latitude='$latitude', 
        longitude='$longitude', 
        logo='$logo'
        WHERE id='$restaurant_id'");

    // Refresh data
    $res = mysqli_query($con, "SELECT * FROM restaurants WHERE id='$restaurant_id'");
    $restaurant = mysqli_fetch_assoc($res);

    $success = "Profile updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Restaurant Profile</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {font-family:'Segoe UI', sans-serif; background:#f4f6f9; margin:0; padding:0;}
.sidebar {
    width:240px;
    background:#e67e22;
    min-height:100vh;
    position:fixed;
    color:#fff;
    padding-top:20px;
    text-align:center;
}
.sidebar img.profile-logo {
    width:80px;
    height:80px;
    object-fit:cover;
    border-radius:50%;
    margin-bottom:10px;
    border:2px solid #fff;
}
.sidebar h5 {margin-bottom:20px; font-weight:600;}
.sidebar a {
    display:flex; align-items:center; gap:10px;
    padding:12px 20px; color:#fff; text-decoration:none;
    margin-bottom:5px; border-radius:8px; transition:0.3s;
}
.sidebar a:hover {background:#cf711f;}
.sidebar i {font-size:1.2rem;}
.main-content {margin-left:260px; padding:20px;}
.card {border-radius:15px; box-shadow:0 5px 15px rgba(0,0,0,0.1);}
</style>
</head>
<body>


<div class="sidebar">
    <img src="uploads/<?php echo $restaurant['logo'] ? $restaurant['logo'] : 'tastbites.png'; ?>" class="profile-logo" alt="Tastbites">
    <h5><?php echo htmlspecialchars($restaurant_name ?? 'Tastbites'); ?></h5>
    <a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="orders.php"><i class="bi bi-cart"></i> Orders</a>
    <a href="menu.php"><i class="bi bi-list-ul"></i> Menu Items</a>
    <a href="profile.php"><i class="bi bi-person-circle"></i> Profile</a>
    <a href="analytics.php"><i class="bi bi-bar-chart-line"></i> Analytics</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="card p-4">
        <h4>Restaurant Profile</h4>
        <?php if(isset($success)){ echo "<div class='alert alert-success'>$success</div>"; } ?>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Restaurant Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($restaurant['name']); ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($restaurant['address']); ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Contact</label>
                <input type="text" name="contact" value="<?php echo htmlspecialchars($restaurant['contact']); ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Latitude</label>
                <input type="text" name="latitude" value="<?php echo htmlspecialchars($restaurant['latitude']); ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Longitude</label>
                <input type="text" name="longitude" value="<?php echo htmlspecialchars($restaurant['longitude']); ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Logo</label><br>
                <?php if($restaurant['logo']){ ?>
                    <img src="uploads/<?php echo $restaurant['logo']; ?>" width="100" class="mb-2">
                <?php } ?>
                <input type="file" name="logo" class="form-control">
            </div>

            <button type="submit" name="update_profile" class="btn btn-warning">Update Profile</button>
        </form>
    </div>
</div>

</body>
</html>