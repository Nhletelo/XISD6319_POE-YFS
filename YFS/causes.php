<?php
require_once 'config/db_conn.php';

// Fetch causes
$sql = "SELECT * FROM causes ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Causes - Youth For Survival</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Link to the CSS file -->
      <link rel="stylesheet" href="css/causes.css"

      <!-- Link JS with defer -->
      <script defer src="js/causes.js"></script>
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
    <button class="menu-toggle" aria-label="Toggle navigation menu">
        <span></span>
        <span></span>
        <span></span>
    </button>
    <nav id="main-nav">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About us</a></li>
            <li><a href="causes.php" class="active">Causes</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="news.php">News</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="Register.php">Register</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>
</header>

<!-- Hero Section -->
<section class="hero">
    <h1>Our Causes</h1>
    <p>Discover the initiatives and campaigns we're passionate about at Youth For Survival. Together, we can make a difference in our communities.</p>
</section>

<!-- Causes Section -->
<div class="container">
    <h2 class="section-title">Current Initiatives</h2>

    <div class="causes-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                
                    <div class="cause-content">
                        <h3 class="cause-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p class="cause-description"><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                        <div class="cause-date">
                            <i class="far fa-calendar-alt"></i>
                            Posted on: <?php echo date('F j, Y', strtotime($row['created_at'])); ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-hands-helping"></i>
                <p>No causes added yet. Please check back later!</p>
                <a href="index.php" class="cta-btn">Return Home</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Call to Action Section -->
<section class="cta-section">
    <h2>Want to Support Our Causes?</h2>
    <p>Your donation can help us continue our vital work in the community. Every contribution makes a difference.</p>
    <a href="donate.php" class="cta-btn">Make a Donation</a>
</section>

<!-- Footer -->
<?php include('footer.php'); ?>


</body>
</html>