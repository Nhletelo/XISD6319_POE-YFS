<?php
// Start a session
session_start();
require_once 'config/db_conn.php';

// Initialize variables
$fullname = $email = $username = $role = '';
$errors = [];
$success = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $role = $_POST['role'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($fullname)) {
        $errors['fullname'] = 'Full name is required';
    } elseif (strlen($fullname) < 2) {
        $errors['fullname'] = 'Full name must be at least 2 characters';
    }
    
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address';
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM app_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $errors['email'] = 'Email is already registered';
        }
        $stmt->close();
    }
    
    if (empty($username)) {
        $errors['username'] = 'Username is required';
    } elseif (strlen($username) < 3) {
        $errors['username'] = 'Username must be at least 3 characters';
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM app_users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $errors['username'] = 'Username is already taken';
        }
        $stmt->close();
    }
    
    if (empty($role)) {
        $errors['role'] = 'Please select a role';
    }
    
    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Password must be at least 6 characters';
    }
    
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Passwords do not match';
    }
    
    // If no errors, register the user
    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO app_users (fullname, email, username, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $fullname, $email, $username, $hashed_password, $role);
        
        if ($stmt->execute()) {
            $success = 'Registration successful! You can now <a href="Login.php" style="color: #2ecc71; text-decoration: underline;">login</a>.';
            
            // Clear form fields
            $fullname = $email = $username = $role = '';
        } else {
            $errors['database'] = 'Registration failed. Please try again later.';
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
    <title>Register | Youth For Survival</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!Link to the CSS file-->
    <link rel="stylesheet" href="css/Register.css"

    <!Link to JS file-->
    <script defer src="js/Register.js"></script>

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
            <li><a href="contact.php">Contact</a></li>
            <li><a href="Register.php" class="active">Register</a></li>
            <li><a href="Login.php">Login</a></li>    
        </ul>
    </nav>
</header>

<!-- Registration Form Section -->
<section class="form-section">
    <div class="form-container">
        <div class="form-header">
            <h2>Create an Account</h2>
            <p>Join Youth for Survival and make a difference</p>
        </div>
        
        <?php if (!empty($success)): ?>
            <div class="form-success">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <form action="Register.php" method="POST" id="registrationForm">
            <div class="form-group">
                <label for="fullname" class="form-label">Full Name</label>
                <input type="text" id="fullname" name="fullname" class="form-input" 
                       value="<?php echo htmlspecialchars($fullname); ?>" 
                       placeholder="Enter your full name" required>
                <?php if (isset($errors['fullname'])): ?>
                    <div class="form-error"><i class="fas fa-exclamation-circle"></i> <?php echo $errors['fullname']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" class="form-input" 
                       value="<?php echo htmlspecialchars($email); ?>" 
                       placeholder="Enter your email address" required>
                <?php if (isset($errors['email'])): ?>
                    <div class="form-error"><i class="fas fa-exclamation-circle"></i> <?php echo $errors['email']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-input" 
                       value="<?php echo htmlspecialchars($username); ?>" 
                       placeholder="Choose a username" required>
                <?php if (isset($errors['username'])): ?>
                    <div class="form-error"><i class="fas fa-exclamation-circle"></i> <?php echo $errors['username']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="role" class="form-label">I want to join as a</label>
                <select id="role" name="role" class="form-select" required>
                    <option value="">Select your role</option>
                    <option value="volunteer" <?php echo $role === 'volunteer' ? 'selected' : ''; ?>>Volunteer</option>
                    <option value="admin" <?php echo $role === 'admin' ? 'selected' : ''; ?>>Administrator</option>
                </select>
                <?php if (isset($errors['role'])): ?>
                    <div class="form-error"><i class="fas fa-exclamation-circle"></i> <?php echo $errors['role']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" class="form-input" 
                           placeholder="Create a password" required>
                    <span class="toggle-password" id="togglePassword">
                        <i class="far fa-eye"></i>
                    </span>
                </div>
                <div class="password-strength">
                    <div class="password-strength-bar" id="passwordStrengthBar"></div>
                </div>
                <div class="password-rules">
                    <i class="fas fa-info-circle"></i> Must be at least 6 characters
                </div>
                <?php if (isset($errors['password'])): ?>
                    <div class="form-error"><i class="fas fa-exclamation-circle"></i> <?php echo $errors['password']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <div class="password-container">
                    <input type="password" id="confirm_password" name="confirm_password" class="form-input" 
                           placeholder="Confirm your password" required>
                    <span class="toggle-password" id="toggleConfirmPassword">
                        <i class="far fa-eye"></i>
                    </span>
                </div>
                <?php if (isset($errors['confirm_password'])): ?>
                    <div class="form-error"><i class="fas fa-exclamation-circle"></i> <?php echo $errors['confirm_password']; ?></div>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="btn">Create Account</button>
            
            <div class="form-footer">
                <p>Already have an account? <a href="Login.php">Sign in here</a></p>
            </div>
        </form>
    </div>
</section>

<!-- Footer -->
<footer>
    <?php include('footer.php'); ?>
</footer>


</body>
</html>