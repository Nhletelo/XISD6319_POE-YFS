<?php
require_once 'config/db_conn.php';

// Fetch all images from the gallery table
$images = $conn->query("SELECT * FROM gallery ORDER BY created_at DESC");

// Fetch categories if they exist in your database
$categories = [];
$category_result = $conn->query("SHOW COLUMNS FROM gallery LIKE 'category'");
if ($category_result->num_rows > 0) {
    $cat_query = $conn->query("SELECT DISTINCT category FROM gallery WHERE category IS NOT NULL AND category != ''");
    while ($cat_row = $cat_query->fetch_assoc()) {
        $categories[] = $cat_row['category'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery - Youth For Survival</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css">
    
    <!-- Link to the CSS file -->
      <link rel="stylesheet" href="css/gallery.css"

      <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
      <!-- Link JS with defer -->
      <script defer src="js/gallery.js"></script>
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    <button class="menu-toggle" aria-label="Toggle navigation menu">
        <span></span>
        <span></span>
        <span></span>
    </button>
    <a href="donate.php" class="donate-btn">Donate Now</a>
</div>

<!-- Navigation -->
<header>
    <div class="logo">
        <img src="images/logo_large.webp" alt="Youth For Survival Logo">
        Youth<span>For Survival</span>
    </div>
    <nav id="main-nav">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About us</a></li>
            <li><a href="causes.php">Causes</a></li>
            <li><a href="gallery.php" class="active">Gallery</a></li>
            <li><a href="news.php">News</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="Register.php">Register</a></li>
            <li><a href="login.php">Login</a></li>            
        </ul>
    </nav>
</header>

<!-- Page Header -->
<section class="page-header">
    <h1>Our Gallery</h1>
    <ul class="breadcrumb">
        <li><a href="index.php">Home</a></li>
        <li class="separator">/</li>
        <li>Gallery</li>
    </ul>
</section>

<!-- Gallery Controls -->
<div class="gallery-controls">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="search-input" placeholder="Search images...">
    </div>
    
    <div class="filter-buttons">
        <button class="filter-btn active" data-filter="all">All</button>
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $category): ?>
                <button class="filter-btn" data-filter="<?php echo htmlspecialchars(strtolower($category)); ?>">
                    <?php echo htmlspecialchars($category); ?>
                </button>
            <?php endforeach; ?>
        <?php else: ?>
            <button class="filter-btn" data-filter="events">Events</button>
            <button class="filter-btn" data-filter="activities">Activities</button>
            <button class="filter-btn" data-filter="volunteers">Volunteers</button>
        <?php endif; ?>
    </div>
    
    <div class="view-options">
        <button class="view-btn active" id="grid-view"><i class="fas fa-th"></i></button>
        <button class="view-btn" id="list-view"><i class="fas fa-list"></i></button>
    </div>
</div>

<!-- Gallery Section -->
<div class="gallery-container">
    <div class="gallery grid-view" id="image-gallery">
        <?php if ($images->num_rows > 0): ?>
            <?php while ($row = $images->fetch_assoc()) : 
                $category = !empty($row['category']) ? $row['category'] : 'Uncategorized';
                $date = !empty($row['created_at']) ? date('M j, Y', strtotime($row['created_at'])) : 'Unknown date';
            ?>
                <div class="gallery-item" data-category="<?php echo htmlspecialchars(strtolower($category)); ?>" data-title="<?php echo htmlspecialchars(!empty($row['caption']) ? $row['caption'] : 'Image'); ?>">
                    <a href="<?php echo htmlspecialchars($row['image_path']); ?>" data-lightbox="gallery" data-title="<?php echo htmlspecialchars(!empty($row['caption']) ? $row['caption'] : 'Youth For Survival Image'); ?>">
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars(!empty($row['caption']) ? $row['caption'] : 'Gallery Image'); ?>" class="gallery-img">
                    </a>
                    <div class="gallery-details">
                        <span class="gallery-category"><?php echo htmlspecialchars($category); ?></span>
                        <h3><?php echo !empty($row['caption']) ? htmlspecialchars($row['caption']) : 'Youth For Survival'; ?></h3>
                        <p><?php echo !empty($row['description']) ? htmlspecialchars($row['description']) : 'Our activities and events empowering youth.'; ?></p>
                        <div class="gallery-meta">
                            <span><i class="far fa-calendar"></i> <?php echo $date; ?></span>
                            <span><i class="far fa-image"></i> Gallery</span>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-results">
                <i class="fas fa-image"></i>
                <h3>No Images Yet</h3>
                <p>We haven't uploaded any images to our gallery yet. Please check back soon!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include('footer.php'); ?>


</body>
</html>