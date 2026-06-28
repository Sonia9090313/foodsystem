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
        mysqli_query($con, "UPDATE users SET status='approved' WHERE id='$id' AND role='restaurant'");
    } elseif($action == 'reject'){
        mysqli_query($con, "UPDATE users SET status='rejected' WHERE id='$id' AND role='restaurant'");
    } elseif($action == 'delete'){
        mysqli_query($con, "DELETE FROM users WHERE id='$id'");
    }

    header("Location: users.php");
    exit();
}

// Fetch all users from DB
$res = mysqli_query($con, "SELECT * FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {background:#f0f2f5; font-family:'Segoe UI', sans-serif;}

/* Sidebar (same as dashboard) */
.sidebar {
    width:220px;
    background:#ff7f50;
    min-height:100vh;
    position:fixed;
    color:#fff;
    padding-top:20px;
}
.sidebar h4 {
    text-align:center; 
    margin-bottom:20px;
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

/* Main content */
.main-content {
    margin-left:240px;
    padding:20px;
}

/* Cards and table styling */
.card {border-radius:12px; box-shadow:0 5px 12px rgba(0,0,0,0.1);}
.btn-orange {background:#ff7f50; color:#fff;}
.btn-orange:hover {background:#ff5722; color:#fff;}
.table th {background:#ff7f50; color:#fff; text-align:center;}
.table td {vertical-align:middle; text-align:center;}
.badge-role {padding:0.5em 0.7em; font-size:0.9em;}
</style>
</head>
<body>


<?php include "navbar.php" ?>


<div class="main-content">
    <h3 class="mb-4 text-secondary">👥 Manage Users</h3>
    <div class="card p-3">
        <table class="table table-bordered align-middle table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if($res && mysqli_num_rows($res) > 0): ?>
                <?php while($user = mysqli_fetch_assoc($res)): ?>
                    <tr>
                        <td><?= $user['id']; ?></td>
                        <td><?= htmlspecialchars($user['name']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td>
                            <?php
                                $role_class = 'bg-info';
                                if($user['role']=='restaurant') $role_class='bg-warning';
                                elseif($user['role']=='rider') $role_class='bg-secondary';
                                echo "<span class='badge badge-role $role_class'>".ucfirst($user['role'])."</span>";
                            ?>
                        </td>
                        <td>
                            <?php
                                $status_class = 'bg-info';
                                if($user['role']=='restaurant'){
                                    if($user['status']=='pending') $status_class='bg-warning';
                                    elseif($user['status']=='approved') $status_class='bg-success';
                                    elseif($user['status']=='rejected') $status_class='bg-danger';
                                    echo "<span class='badge badge-role $status_class'>".ucfirst($user['status'])."</span>";
                                } else {
                                    echo "<span class='badge badge-role bg-success'>Active</span>";
                                }
                            ?>
                        </td>
                        <td>
                            <?php if($user['role']=='restaurant' && $user['status']=='pending'): ?>
                                <a href="users.php?id=<?= $user['id']; ?>&action=approve" class="btn btn-sm btn-success mb-1">Approve</a>
                                <a href="users.php?id=<?= $user['id']; ?>&action=reject" class="btn btn-sm btn-warning mb-1">Reject</a>
                            <?php endif; ?>
                            <a href="users.php?id=<?= $user['id']; ?>&action=delete" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6">No users found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>