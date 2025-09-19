<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youth For Survival - Contact Us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Link to the CSS file -->
      <link rel="stylesheet" href="css/contact.css"

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
            <li><a href="news.php">News</a></li>
            <li><a href="contact.php" class="active">Contact</a></li>
            <li><a href="Register.php">Register</a></li> 
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>
</header>

<!-- Page Header -->
<section class="page-header">
    <h1>Get In Touch</h1>
    <ul class="breadcrumb">
        <li><a href="index.php">Home</a></li>
        <li class="separator">/</li>
        <li>Contact Us</li>
    </ul>
</section>

<!-- Contact Section -->
<div class="contact-container">
    <div class="contact-info">
        <h2>Contact Information</h2>
        
        <div class="info-item">
            <div class="info-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="info-content">
                <h3>Email</h3>
                <a href="mailto:youthforsurvival2007@gmail.com">youthforsurvival2007@gmail.com</a>
            </div>
        </div>
        
        <div class="info-item">
            <div class="info-icon">
                <i class="fab fa-linkedin"></i>
            </div>
            <div class="info-content">
                <h3>LinkedIn</h3>
                <a href="https://www.linkedin.com/company/youth-for-survival-south-africa" target="_blank">Connect with us</a>
            </div>
        </div>
        
        <div class="info-item">
            <div class="info-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="info-content">
                <h3>Address</h3>
                <p>Pretoria, Gauteng, South Africa</p>
            </div>
        </div>
        
        <div class="social-links">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
    </div>
    
    <div class="contact-form">
        <h2>Send Us a Message</h2>
        <div class="success-message" id="successMessage">
            Thank you for your message! We'll get back to you soon.
        </div>
        <form id="contactForm" action="#" method="post">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Your Full Name" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Your Email Address" required>
            </div>
            
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" class="form-control" placeholder="Your Message..." required></textarea>
            </div>
            
            <button type="submit" class="submit-btn">Send Message</button>
        </form>
    </div>
</div>

<!-- Map Section -->
<section class="map-section">
    <h2>Our Location</h2>
    <div class="map-container">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d114600.38839385526!2d28.167054299999997!3d-25.747448!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1e95618d36f27d61%3A0x7cd5d3e1b6f6ed6f!2sPretoria%2C%20South%20Africa!5e0!3m2!1sen!2sus!4v1654567890123!5m2!1sen!2sus" allowfullscreen="" loading="lazy"></iframe>
    </div>
</section>

<!-- Footer -->
<?php include('footer.php'); ?>

<script>
    // Form submission handling
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        document.getElementById('successMessage').style.display = 'block';
        
        // Scroll to success message
        document.getElementById('successMessage').scrollIntoView({behavior: 'smooth'});
        
        // Reset the form
        this.reset();
    });
</script>

</body>
</html>