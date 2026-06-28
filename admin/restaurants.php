<?php
session_start();
include 'db.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

// Fetch restaurants
$sql = "SELECT * FROM restaurants ORDER BY id DESC";
$res = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Restaurants</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {background:#f9f9f9; font-family:'Segoe UI', sans-serif;}


.sidebar {
    width:220px;
    background:#ff7f50;
    min-height:100vh;
    position:fixed;
    color:#fff;
    padding-top:20px;
    display:flex;
    flex-direction:column;
}
.sidebar h4 {text-align:center;
 margin-bottom:20px;
}
.sidebar a {
    display:flex;
     align-items:center;
      gap:10px;
    padding:12px 20px; 
    color:#fff; 
    text-decoration:none;
     margin-bottom:5px;
      border-radius:8px;
}
.sidebar a:hover {
    background:#ff5722;
}
.sidebar a.active {background:#e26f47;}


.main-content {margin-left:240px; 
    padding:20px;
}
h3 {color:#FF7F50;
 margin-bottom:20px;
}
.table th {background:#FF7F50;
 color:#fff;
}
.card {border-radius:10px; 
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
     margin-bottom:20px;
 }
.btn-orange, .btn-orange:hover {
    background:#FF7F50;
     color:#fff; border:none;
 }
</style>
</head>
<body>


<?php include "navbar.php" ?>

<div class="main-content">
    <h3>🏪 Manage Restaurants</h3>

    <div class="card p-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Restaurant Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Registered At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if(mysqli_num_rows($res) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($res)): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td>
                        <span class="badge bg-<?= ($row['status']=='approved'?'success':($row['status']=='rejected'?'danger':'warning')); ?>">
                            <?= ucfirst($row['status']); ?>
                        </span>
                    </td>
                    <td><?= $row['created_at']; ?></td>
                    <td>
                        <?php if($row['status']=='pending'): ?>
                            <a href="restaurant_action.php?id=<?= $row['id']; ?>&action=approve" class="btn btn-sm btn-success"><i class="bi bi-check-circle"></i> Approve</a>
                            <a href="restaurant_action.php?id=<?= $row['id']; ?>&action=reject" class="btn btn-sm btn-warning"><i class="bi bi-x-circle"></i> Reject</a>
                        <?php endif; ?>
                        <a href="restaurant_action.php?id=<?= $row['id']; ?>&action=delete" class="btn btn-sm btn-danger" onclick="return confirm('Delete this restaurant?')"><i class="bi bi-trash"></i> Delete</a>
                        <a href="menu.php?rid=<?= $row['id']; ?>" class="btn btn-sm btn-orange"><i class="bi bi-card-list"></i> View Menu</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">No restaurants found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>