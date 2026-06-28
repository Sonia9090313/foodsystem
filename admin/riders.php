<?php
session_start();
include 'db.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

// Handle actions: approve/reject/delete
if(isset($_GET['id']) && isset($_GET['action'])){
    $id = (int)$_GET['id'];
    $action = $_GET['action'];

    if($action == 'approve'){
        mysqli_query($con, "UPDATE riders SET status='approved' WHERE id='$id'");
    } elseif($action == 'reject'){
        mysqli_query($con, "UPDATE riders SET status='rejected' WHERE id='$id'");
    } elseif($action == 'delete'){
        mysqli_query($con, "DELETE FROM riders WHERE id='$id'");
    }

    header("Location: riders.php");
    exit();
}

// Fetch all riders
$res = mysqli_query($con, "SELECT * FROM riders ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Riders</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {background:#f0f2f5;
 font-family:'Segoe UI', sans-serif;
}
.sidebar {
    width:220px;
     background:#ff7f50;
      min-height:100vh; 
      position:fixed;
       color:#fff;
        padding-top:20px;
}
.sidebar a {
    display:block; 
    padding:12px 20px;
     color:#fff; 
     text-decoration:none; 
     margin-bottom:5px;
      border-radius:8px;
  }
.sidebar a:hover {
    background:#ff5722;
}
.main-content {
    margin-left:240px;
     padding:20px;
 }
.card {border-radius:12px;
 box-shadow:0 5px 12px rgba(0,0,0,0.1);
}
.table th {background:#ff7f50; 
    color:#fff; text-align:center;
}
.table td {vertical-align:middle;
 text-align:center;}
.btn-orange {background:#ff7f50;
 color:#fff;}
.btn-orange:hover {background:#ff5722; 
    color:#fff;
}
.badge-status {padding:0.5em 0.7em;
 font-size:0.9em;
}
</style>
</head>
<body>


<?php include "navbar.php" ?>


<div class="main-content">
    <h3 class="mb-4 text-secondary">🛵 Manage Riders</h3>
    <div class="card p-3">
        <table class="table table-bordered align-middle table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Registered At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if($res && mysqli_num_rows($res) > 0): ?>
                <?php while($rider = mysqli_fetch_assoc($res)): ?>
                    <tr>
                        <td><?= $rider['id']; ?></td>
                        <td><?= htmlspecialchars($rider['name']); ?></td>
                        <td><?= htmlspecialchars($rider['email']); ?></td>
                        <td>
                            <?php
                                $status_class = 'bg-info';
                                if($rider['status']=='pending') $status_class='bg-warning';
                                elseif($rider['status']=='approved') $status_class='bg-success';
                                elseif($rider['status']=='rejected') $status_class='bg-danger';
                                echo "<span class='badge badge-status $status_class'>".ucfirst($rider['status'])."</span>";
                            ?>
                        </td>
                        <td><?= $rider['created_at']; ?></td>
                        <td>
                            <?php if($rider['status']=='pending'): ?>
                                <a href="riders.php?id=<?= $rider['id']; ?>&action=approve" class="btn btn-sm btn-success mb-1">Approve</a>
                                <a href="riders.php?id=<?= $rider['id']; ?>&action=reject" class="btn btn-sm btn-warning mb-1">Reject</a>
                            <?php endif; ?>
                            <a href="riders.php?id=<?= $rider['id']; ?>&action=delete" class="btn btn-sm btn-danger" onclick="return confirm('Delete this rider?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6">No riders found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>