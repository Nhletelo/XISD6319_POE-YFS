<?php
session_start();
require_once 'config/db_conn.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$adminName = $_SESSION['fullname'] ?? 'Admin';
$adminId = $_SESSION['user_id'];

// Get admin stats
$newsQuery = $conn->query("SELECT COUNT(*) as total_news FROM news");
$totalNews = $newsQuery->fetch_assoc()['total_news'];

$app_usersQuery = $conn->query("SELECT COUNT(*) as total_app_users FROM app_users");
$totalapp_users = $newsQuery->fetch_assoc()['total_app_users'];


$causesQuery = $conn->query("SELECT COUNT(*) as total_causes FROM causes");
$totalCauses = $causesQuery->fetch_assoc()['total_causes'];

$donationsQuery = $conn->query("SELECT COUNT(*) as total_donations FROM donations");
$totalDonations = $donationsQuery->fetch_assoc()['total_donations'];

$volunteersQuery = $conn->query("SELECT COUNT(*) as total_volunteers FROM app_users WHERE role = 'volunteer'");
$totalVolunteers = $volunteersQuery->fetch_assoc()['total_volunteers'];

$messagesQuery = $conn->prepare("SELECT COUNT(*) as unread_messages FROM messages WHERE receiver_id = ? AND is_read = 0");
$messagesQuery->bind_param("i", $adminId);
$messagesQuery->execute();
$messagesResult = $messagesQuery->get_result();
$unreadMessages = $messagesResult->fetch_assoc()['unread_messages'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Youth For Survival</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <!-- Link to the CSS file -->
      <link rel="stylesheet" href="css/admin_dashboard.css"
</head>
<body>

<div class="menu-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</div>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h2>Admin Panel</h2>
        <p>Welcome, <span class="admin-name"><?php echo htmlspecialchars($adminName); ?></span></p>
    </div>
    
    <div class="sidebar-menu">
        <a href="admin_dashboard.php" class="menu-item active">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="admin_gallery.php" class="menu-item">
            <i class="fas fa-images"></i>
            <span>Manage Gallery</span>
        </a>
        <a href="manage_news.php" class="menu-item">
            <i class="fas fa-newspaper"></i>
            <span>Manage News</span>
            <?php if ($totalNews > 0): ?>
                <span class="badge"><?php echo $totalNews; ?></span>
            <?php endif; ?>
        </a>
        <a href="manage_causes.php" class="menu-item">
            <i class="fas fa-hand-holding-heart"></i>
            <span>Manage Causes</span>
            <?php if ($totalCauses > 0): ?>
                <span class="badge"><?php echo $totalCauses; ?></span>
            <?php endif; ?>
        </a>

        
        <a href="users_management.php" class="menu-item">
            <i class="fas fa-user"></i>
            <span>User Management</span>
            <?php if ($totalapp_users > 0): ?>
                <span class="badge"><?php echo $totalapp_users; ?></span>
            <?php endif; ?>
        </a>

        <a href="admin_view_donations.php" class="menu-item">
            <i class="fas fa-donate"></i>
            <span>Manage Donations</span>
            <?php if ($totalDonations > 0): ?>
                <span class="badge"><?php echo $totalDonations; ?></span>
            <?php endif; ?>
        </a>
       
        </a>
        <a href="message.php" class="menu-item">
            <i class="fas fa-paper-plane"></i>
            <span>Send Message</span>
        </a>
        <a href="adminAssign_task.php" class="menu-item">
            <i class="fas fa-tasks"></i>
            <span>Assign Task</span>
        </a>
        <a href="inbox.php" class="menu-item">
            <i class="fas fa-inbox"></i>
            <span>Inbox</span>
            <?php if ($unreadMessages > 0): ?>
                <span class="badge"><?php echo $unreadMessages; ?></span>
            <?php endif; ?>
        </a>
    </div>
    
    <div class="logout-container">
        <a href="logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</div>

<div class="content">
    <div class="page-header">
        <h1>Admin Dashboard</h1>
        <p>Welcome back, <?php echo htmlspecialchars($adminName); ?></p>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon news-icon">
                <i class="fas fa-newspaper"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $totalNews; ?></h3>
                <p>News Articles</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon causes-icon">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $totalCauses; ?></h3>
                <p>Active Causes</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon donations-icon">
                <i class="fas fa-donate"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $totalDonations; ?></h3>
                <p>Total Donations</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon volunteers-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $totalVolunteers; ?></h3>
                <p>Registered Volunteers</p>
            </div>
        </div>
    </div>
    
    <div class="quick-actions">
        <h2 class="section-title">Quick Actions</h2>
        <div class="actions-grid">
            <a href="manage_news.php" class="action-btn">
                <i class="fas fa-plus-circle"></i>
                <span>Add News</span>
            </a>
            <a href="manage_causes.php" class="action-btn">
                <i class="fas fa-plus-circle"></i>
                <span>Add Cause</span>
            </a>
            <a href="admin_view_donations.php" class="action-btn">
                <i class="fas fa-eye"></i>
                <span>View Donations</span>
            </a>
            <a href="message.php" class="action-btn">
                <i class="fas fa-paper-plane"></i>
                <span>Send Message</span>
            </a>
        </div>
    </div>
    
    
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('active');
    }
    
    // Auto-hide sidebar on mobile after selection
    if (window.innerWidth < 576) {
        const menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            item.addEventListener('click', () => {
                document.getElementById('sidebar').classList.remove('active');
            });
        });
    }
</script>
</body>
</html>