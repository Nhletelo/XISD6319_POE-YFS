<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youth For Survival - About Us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Link to the CSS file -->
      <link rel="stylesheet" href="css/about.css">

      <!-- Link to the JS file -->
      <script defer src = "js/about.js"></script>
</head>
<body>
    <!-- Topbar -->
    <div class="topbar">
        <a href="donate.php" class="donate-btn">Donate Now</a>
    </div>

    <!-- Header -->
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
                <li><a href="about.php" class="active">About us</a></li>
                <li><a href="causes.php">Causes</a></li>
                <li><a href="gallery.php">Gallery</a></li>
                <li><a href="news.php">News</a></li>
                <li><a href="contact.php">Contact</a></li> 
                <li><a href="Register.php">Register</a></li>
                <li><a href="login.php">Login</a></li>    
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="container">
        <h1>About Youth For Survival (YFS)</h1>
        
        <p>Youth for Survival (YFS) is a registered Not-For-Profit Organization founded in 2004 and registered in 2007, with a robust national presence across South Africa. Our head office is based in Pretoria, Gauteng, and we extend our reach through branch satellites in:</p>
        <ul>
            <li>Moloto North, Mpumalanga</li>
            <li>Maboloka, North West</li>
            <li>Nongoma, KwaZulu-Natal</li>
            <li>Port St. Johns, Eastern Cape</li>
        </ul>
        <p>YFS is committed to addressing the urgent challenges of GBV, social inequality, and economic hardship disadvantaged communities face. Our focus is on empowering women, children, and youth by providing comprehensive support systems that foster sustainable change.</p>

        <h2>Our Core Initiatives:</h2>
        <ul>
            <li><strong>Safe Houses:</strong> Secure shelters and holistic care for GBV survivors.</li>
            <li><strong>Nutritional Support:</strong> Daily soup kitchens and food distribution.</li>
            <li><strong>Skills Training:</strong> Capacity-building workshops for unemployed youth.</li>
            <li><strong>Community Development:</strong> Resources and social support for households in need.</li>
        </ul>

        <p>At our Advice Centre, we offer counseling and guidance to help individuals overcome personal and social challenges. Through these programs, YFS is committed to uplifting marginalized communities and improving their quality of life holistically.</p>

        <div class="about-details">
            <p><strong>Phone:</strong> 012 304 0001</p>
            <p><strong>Industry:</strong> Non-Profit Organizations</p>
            <p><strong>Company Size:</strong> 11–50 employees</p>
            <p><strong>Headquarters:</strong> City of Tshwane, Gauteng</p>
            <p><strong>Founded:</strong> 2005</p>
        </div>

        <div class="map-section">
            <h2>Our Location</h2>
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3593.7833450713233!2d28.170881874358656!3d-25.74467737736258!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1e956273cb23062b%3A0x1637a7a3751ce844!2syouth%20for%20survival!5e0!3m2!1sen!2sza!4v1745588204500!5m2!1sen!2sza" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>

    
    <!-- Footer -->
<?php include('footer.php'); ?>

    <!-- Chatbot Button -->
    <button class="chatbot-btn" onclick="toggleChatbot()">
        <i class="fas fa-comments"></i>
    </button>

    <!-- Chatbot Window -->
    <div class="chatbot-window" id="chatbot">
        <div class="chatbot-header">
            <span>YFS Chat Support</span>
            <button class="close-chatbot" onclick="toggleChatbot()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="chatbot-messages" id="chatbotMessages">
            <div class="message bot-message">Hello! How can I help you learn more about Youth For Survival?</div>
        </div>
        <div class="chatbot-input">
            <input type="text" id="chatbotInput" placeholder="Ask about YFS...">
            <button onclick="sendMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>

    
</body>
</html>