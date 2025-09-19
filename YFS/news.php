<?php
session_start();
require_once 'config/db_conn.php'; 

// Fetch news
$news = $conn->query("SELECT * FROM news ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Latest News - Youth For Survival</title>
    <! Link to the CSS file-->
    <link rel= "stylesheet" href="css/news.css"
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    <a href="donate.php" class="donate-btn">Donate Now</a>
</div>

<!-- Navigation -->
<header>
    <div class="logo">
        <img src="images/logo_large.webp" alt="Youth For Survival Logo">
        Youth<span>For Survival</span>
    </div>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About us</a></li>
            <li><a href="causes.php">Causes</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="news.php" class="active">News</a></li>
            <li><a href="contact.php">Contact</a></li>
             <li><a href="Register.php">Register</a></li>
            <li><a href="login.php">Login</a></li>
                      </ul>
    </nav>
</header>

<!-- News Section -->
<div class="container">
    <h1>Latest News</h1>

    <?php if ($news->num_rows > 0): ?>
        <?php while ($row = $news->fetch_assoc()) : ?>
            <div class="news-item">
                <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                <small>Posted on: <?php echo date('F j, Y', strtotime($row['created_at'])); ?></small>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center;">No news posted yet. Please check back later!</p>
    <?php endif; ?>
</div>

<!-- Footer -->
<?php include('footer.php'); ?>

</body>
</html>
