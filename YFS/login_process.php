<?php
session_start();
require_once 'config/db_conn.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize user input
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo "Both fields are required.";
        exit;
    }

    // Prepare SQL query
    $stmt = $conn->prepare("SELECT * FROM app_users WHERE username = ? OR email = ?");
    if (!$stmt) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        exit;
    }
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            
            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
                exit;
            } elseif ($user['role'] === 'volunteer') {
                header("Location: volunteer_dashboard.php");
                exit;
            } else {
                header("Location: user_dashboard.php");
                exit;
            }
        } else {
            echo "Invalid username/email or password.";
            exit;
        }
    } else {
        echo "Invalid username/email or password.";
        exit;
    }

    $stmt->close();
} else {
    header("Location: login.php");
    exit;
}
?>
