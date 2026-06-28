
<div class="sidebar text-center">
    <img src="uploads/<?php echo $logo ?? 'default_logo.png'; ?>" class="profile-logo" alt="Logo">
    <h5 class="mt-2 mb-4"><?php echo htmlspecialchars($restaurant_name ?? 'Restaurant'); ?></h5>
    <a href="dashboard.php"><i class="bi bi-speedometer2"></i> <span>Dashboard</span></a>
    <a href="orders.php"><i class="bi bi-cart"></i> <span>Orders</span></a>
    <a href="menu.php"><i class="bi bi-list-ul"></i> <span>Menu Items</span></a>
    <a href="profile.php"><i class="bi bi-person-circle"></i> <span>Profile</span></a>
    <a href="analytics.php"><i class="bi bi-bar-chart-line"></i> <span>Analytics</span></a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> <span>Logout</span></a>
</div>