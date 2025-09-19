<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youth For Survival - Donation Request</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/donate.css">
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    <a href="donate.php" class="donate-btn">Request to Donate</a>
</div>

<!-- Navigation -->
<header>
    <div class="logo">
        <img src="https://placehold.co/100x50/FFFFFF/000000/png?text=Logo" alt="Youth For Survival Logo">
        Youth<span>For Survival</span>
    </div>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About us</a></li>
            <li><a href="causes.php">Causes</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="news.php">News</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="Register.php">Register</a></li> 
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>
</header>

<!-- Page Header -->
<section class="page-header">
    <h1>Support Our Cause</h1>
    <ul class="breadcrumb">
        <li><a href="index.php">Home</a></li>
        <li class="separator">/</li>
        <li>Request to Donate</li>
    </ul>
</section>

<!-- Donation Process Steps -->
<div class="donation-container">
    <div class="donation-form">
        <h2>How It Works</h2>
        
        <div class="process-steps">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Submit Request</h3>
                <p>Fill out the donation request form with your details</p>
            </div>
            
            <div class="step">
                <div class="step-number">2</div>
                <h3>Admin Review</h3>
                <p>Our team will review your request</p>
            </div>
            
            <div class="step">
                <div class="step-number">3</div>
                <h3>Receive Instructions</h3>
                <p>We'll send you payment instructions via email</p>
            </div>
        </div>
    </div>
</div>

<!-- Donation Section -->
<div class="donation-container">
    <div class="donation-form">
        <h2>Request to Donate</h2>
        
        <div class="info-box">
            <p><i class="fas fa-info-circle"></i> Submit this form to express your interest in making a donation. Our admin team will contact you with payment instructions.</p>
        </div>
        
        <form id="donationRequestForm" action="process_donation.php" method="POST">
            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Your Full Name" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Your Email Address" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" class="form-control" placeholder="Your Phone Number">
            </div>
            
            <div class="form-group">
                <label for="amount">Intended Donation Amount (ZAR)</label>
                <input type="number" id="amount" name="amount" class="form-control" placeholder="500" min="10" step="10">
            </div>
            
            <div class="form-group">
                <label for="preference">Preferred Payment Method</label>
                <select id="preference" name="preference" class="form-control">
                    <option value="">Select preferred method</option>
                    <option value="bank-transfer">Bank Transfer</option>
                    <option value="capitec-payme">Capitec PayMe</option>
                    <option value="credit-card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                    <option value="other">Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="message">Message (Optional)</label>
                <textarea id="message" name="message" class="form-control" placeholder="Any specific instructions or questions..." rows="3"></textarea>
            </div>
            
            <button type="submit" class="submit-btn">
                <i class="fas fa-paper-plane"></i> Submit Request
            </button>
        </form>
        
        <div id="formMessage" class="message" style="display: none;"></div>
    </div>
    
    <div class="donation-form">
        <h2>Other Ways to Support</h2>
        
        <div class="info-box">
            <p><i class="fas fa-hand-holding-heart"></i> Can't make a financial donation? Here are other ways you can support our cause:</p>
        </div>
        
        <ul style="list-style-position: inside; padding: 15px;">
            <li style="margin-bottom: 10px;">Volunteer your time and skills</li>
            <li style="margin-bottom: 10px;">Donate supplies or equipment</li>
            <li style="margin-bottom: 10px;">Share our mission on social media</li>
            <li style="margin-bottom: 10px;">Organize a fundraising event</li>
            <li>Sponsor a specific program or project</li>
        </ul>
        
        <p style="text-align: center; margin-top: 20px;">
            <strong>Contact us directly:</strong><br>
            Email: youthforsurvival2007@gmail.com<br>
            Phone: +27 12 345 6789
        </p>
    </div>
</div>

<script>
    document.getElementById('donationRequestForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form values
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const phone = document.getElementById('phone').value;
        const amount = document.getElementById('amount').value;
        const preference = document.getElementById('preference').value;
        const message = document.getElementById('message').value;
        
        // Simple validation
        if (!name || !email) {
            showMessage('Please fill in all required fields', 'error');
            return;
        }
        
        if (!isValidEmail(email)) {
            showMessage('Please enter a valid email address', 'error');
            return;
        }
        
        // Submit the form via AJAX
        const formData = new FormData(this);
        
        fetch('process_donation.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(`Thank you, ${name}! Your donation request has been submitted. Our admin team will contact you at ${email} with payment instructions.`, 'success');
                document.getElementById('donationRequestForm').reset();
            } else {
                showMessage('There was an error submitting your request. Please try again.', 'error');
            }
        })
        .catch(error => {
            showMessage('There was an error submitting your request. Please try again.', 'error');
        });
    });
    
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    function showMessage(text, type) {
        const messageDiv = document.getElementById('formMessage');
        messageDiv.textContent = text;
        messageDiv.className = `message ${type}`;
        messageDiv.style.display = 'block';
        
        // Scroll to message
        messageDiv.scrollIntoView({ behavior: 'smooth' });
        
        // Hide message after 10 seconds
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 10000);
    }
</script>

</body>
</html>