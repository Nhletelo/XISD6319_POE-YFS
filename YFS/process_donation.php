<?php
require_once 'config/db_conn.php';

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form data
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = isset($_POST['phone']) ? $conn->real_escape_string($_POST['phone']) : '';
    $amount = isset($_POST['amount']) ? $conn->real_escape_string($_POST['amount']) : 0;
    $preference = isset($_POST['preference']) ? $conn->real_escape_string($_POST['preference']) : '';
    $message = isset($_POST['message']) ? $conn->real_escape_string($_POST['message']) : '';
    
    // Insert into database
    $sql = "INSERT INTO donations (name, email, phone, amount, payment_preference, message, donated_at) 
            VALUES ('$name', '$email', '$phone', '$amount', '$preference', '$message', NOW())";
    
    if ($conn->query($sql) === TRUE) {
        // Send email notification to admin (optional)
        $to = "admin@youthforsurvival.org";
        $subject = "New Donation Request";
        $email_message = "A new donation request has been submitted:\n\n";
        $email_message .= "Name: $name\n";
        $email_message .= "Email: $email\n";
        $email_message .= "Phone: $phone\n";
        $email_message .= "Amount: ZAR $amount\n";
        $email_message .= "Payment Preference: $preference\n";
        $email_message .= "Message: $message\n";
        
        $headers = "From: noreply@youthforsurvival.org";
        
        
         mail($to, $subject, $email_message, $headers);
        
        // Return success response
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>