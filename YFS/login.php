<?php
// Start a session
session_start();
require_once 'config/db_conn.php';

// Initialize variables
$username = '';
$errors = [];
$success = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // Validation
    if (empty($username)) {
        $errors['username'] = 'Username or email is required';
    }
    
    if (empty($password)) {
        $errors['password'] = 'Password is required';
    }
    
    // If no errors, attempt login
    if (empty($errors)) {
        // Check if username exists
        $stmt = $conn->prepare("SELECT id, fullname, username, email, password, role FROM app_users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: volunteer_dashboard.php");
                }
                exit;
            } else {
                $errors['password'] = 'Incorrect password';
            }
        } else {
            $errors['username'] = 'Username or email not found';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Youth For Survival</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <! Link to the CSS file-->
    <link rel="stylesheet" href="css/login.css"

</head>
<body>

<!-- Topbar -->
<div class="topbar">
    <a href="donate.php" class="donate-btn">Donate Now</a>
</div>

<!-- Header / Navigation -->
<header>
    <div class="logo">
        <img src="images/logo_large.webp" alt="Youth For Survival Logo">
        <span>Youth For Survival</span>
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
            <li><a href="Login.php" class="active">Login</a></li>
        </ul>
    </nav>
</header>

<!-- Login Form Section -->
<section class="form-section">
    <div class="form-container">
        <div class="form-header">
            <h2>Welcome Back</h2>
            <p>Sign in to your Youth for Survival account</p>
        </div>
        
        <?php if (!empty($errors)): ?>
            <div class="form-error" style="margin-bottom: 20px;">
                <i class="fas fa-exclamation-circle"></i> Please check the form below for errors
            </div>
        <?php endif; ?>
        
        <form action="Login.php" method="POST" id="loginForm">
            <div class="form-group">
                <label for="username" class="form-label">Username or Email</label>
                <input type="text" id="username" name="username" class="form-input" 
                       value="<?php echo htmlspecialchars($username); ?>" 
                       placeholder="Enter your username or email" required>
                <?php if (isset($errors['username'])): ?>
                    <div class="form-error"><i class="fas fa-exclamation-circle"></i> <?php echo $errors['username']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" class="form-input" 
                           placeholder="Enter your password" required>
                    <span class="toggle-password" id="togglePassword">
                        <i class="far fa-eye"></i>
                    </span>
                </div>
                <?php if (isset($errors['password'])): ?>
                    <div class="form-error"><i class="fas fa-exclamation-circle"></i> <?php echo $errors['password']; ?></div>
                <?php endif; ?>
                
                <div class="forgot-password">
                    <a href="forgot_password.php">Forgot your password?</a>
                </div>
            </div>
            
            <button type="submit" class="btn">Sign In</button>
            
            <div class="form-footer">
                <p>Don't have an account? <a href="Register.php">Register here</a></p>
            </div>
        </form>
    </div>
</section>

<!-- Footer -->
<?php include('footer.php'); ?>

<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
    
    // Form validation
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        
        if (!username) {
            e.preventDefault();
            alert('Please enter your username or email');
            document.getElementById('username').focus();
        } else if (!password) {
            e.preventDefault();
            alert('Please enter your password');
            document.getElementById('password').focus();
        }
    });
</script>
</body>
</html>