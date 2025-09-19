<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youth For Survival - Enhanced Footer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            flex: 1;
        }
        
        header {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            color: #2c3e50;
            margin-bottom: 1rem;
            font-size: 2.5rem;
        }
        
        p {
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }
        
        .content {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
        
        /* Footer Styles */
        footer {
            background: #1a1a1a;
            color: #d1d1d1;
            padding: 3rem 1.5rem;
            font-family: 'Arial', sans-serif;
            font-size: 0.875rem;
            line-height: 1.5;
            margin-top: auto;
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-start;
            gap: 2rem;
        }
        
        .footer-col {
            flex: 1;
            min-width: 200px;
        }
        
        .footer-col h3 {
            color: #4da8ff;
            font-size: 1.25rem;
            margin-bottom: 1.25rem;
            font-weight: 600;
            position: relative;
            padding-bottom: 0.5rem;
        }
        
        .footer-col h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 2px;
            background: #ff9900;
        }
        
        .footer-about p {
            margin-bottom: 1.5rem;
            opacity: 0.9;
            line-height: 1.6;
        }
        
        .footer-contact p {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1rem;
        }
        
        .footer-contact i {
            color: #ff9900;
            margin-right: 10px;
            margin-top: 5px;
            font-size: 16px;
        }
        
        .footer-links ul {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 0.75rem;
        }
        
        .footer-links a {
            color: #d1d1d1;
            text-decoration: none;
            transition: all 0.3s ease;
            display: block;
        }
        
        .footer-links a:hover {
            color: #ff9900;
            transform: translateX(5px);
        }
        
        .footer-links a:hover::before {
            content: '▸ ';
            color: #ff9900;
        }
        
        .footer-newsletter p {
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }
        
        .newsletter-form {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .newsletter-form input {
            flex: 1;
            padding: 0.75rem;
            border: none;
            border-radius: 4px;
            background: #2a2a2a;
            color: #fff;
        }
        
        .newsletter-form button {
            background: #ff9900;
            color: #1a1a1a;
            border: none;
            padding: 0.75rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        
        .newsletter-form button:hover {
            background: #ffaa33;
        }
        
        .footer-social {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .footer-social a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: #2a2a2a;
            color: #d1d1d1;
            border-radius: 50%;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer-social a:hover {
            background: #ff9900;
            color: #1a1a1a;
            transform: translateY(-3px);
        }
        
        .footer-bottom {
            max-width: 1200px;
            margin: 2rem auto 0;
            padding-top: 1.5rem;
            border-top: 1px solid #2a2a2a;
            text-align: center;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }
        
        .footer-bottom p {
            margin: 0;
        }
        
        .footer-legal {
            display: flex;
            gap: 1.5rem;
        }
        
        .footer-legal a {
            color: #ff9900;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-legal a:hover {
            color: #4da8ff;
            text-decoration: underline;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .footer-container {
                flex-direction: column;
                gap: 2.5rem;
            }
            
            .footer-col {
                width: 100%;
            }
            
            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }
            
            .footer-legal {
                justify-content: center;
            }
        }
        
        .demo-note {
            background: #ff9900;
            color: #1a1a1a;
            padding: 1rem;
            text-align: center;
            font-weight: 600;
            border-radius: 5px;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    

    <footer>
        <div class="footer-container">
            <div class="footer-col footer-about">
                <h3>Youth For Survival</h3>
                <p>Empowering youth for a better tomorrow through education, support, and community development initiatives. Together, we can create lasting change.</p>
                <div class="footer-social">
                    <a href="https://www.linkedin.com/company/youth-for-survival-south-africa"><i class="fab fa-linkedin-in"></i></a>
                    
                </div>
            </div>
            
            <div class="footer-col footer-links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="causes.php">Causes</a></li>
                    <li><a href="donate.php">Get Involved</a></li>
                </ul>
            </div>
            
            <div class="footer-col footer-contact">
                <h3>Contact Us</h3>
                <p><i class="fas fa-map-marker-alt"></i> 123 Youth Street, Pretoria, Gauteng, South Africa</p>
                <p><i class="fas fa-phone"></i> +27 12 345 6789</p>
                <p><i class="fas fa-envelope"></i>youthforsurvival2007@gmail.com</p>
                <p><i class="fas fa-clock"></i> Mon-Fri: 9:00 AM - 5:00 PM</p>
            </div>
            
            <div class="footer-col footer-newsletter">
                <h3>Newsletter</h3>
                <p>Subscribe to our newsletter to receive updates on our programs and events.</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Your Email Address" required>
                    <button type="submit">Subscribe</button>
                </form>
                <p>We respect your privacy and will never share your information.</p>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <script>document.write(new Date().getFullYear());</script> Youth For Survival. All rights reserved.</p>
            <div class="footer-legal">
                <a href="#"></a>
                <a href="#"></a>
                <a href="#"></a>
                <a href="#"></a>
            </div>
        </div>
    </footer>

    <script>
        // Simple form submission handler
        document.querySelector('.newsletter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input').value;
            alert(`Thank you for subscribing with: ${email}\nYou will receive our updates shortly.`);
            this.reset();
        });
    </script>
</body>
</html>