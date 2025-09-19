<?php
session_start();
require_once 'config/db_conn.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$message = "";

// Fetch all volunteers to populate dropdown/select
$volunteers = [];
$result = $conn->query("SELECT id, fullname, username FROM app_users WHERE role = 'volunteer' ORDER BY fullname");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $volunteers[] = $row;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $volunteer_id = intval($_POST['volunteer_id']);
    $task_title = trim($_POST['task_title']);
    $task_description = trim($_POST['task_description']);
    $due_date = $_POST['due_date'];

    // Basic validation
    if (!$volunteer_id || !$task_title || !$due_date) {
        $message = "Please fill in all required fields.";
    } else {
        $assigned_date = date('Y-m-d');

        $stmt = $conn->prepare("INSERT INTO volunteer_tasks (volunteer_id, task_title, task_description, assigned_date, due_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $volunteer_id, $task_title, $task_description, $assigned_date, $due_date);

        if ($stmt->execute()) {
            $message = "Task assigned successfully.";
        } else {
            $message = "Error assigning task: " . $conn->error;
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
    <title>Assign Task to Volunteer</title>
     <!-- Link to the CSS file -->
      <link rel="stylesheet" href="css/adminAssign_task.css"

</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Assign Task to Volunteer</h1>
            <p class="subtitle">Create and assign tasks to volunteers</p>
        </div>
        
        <?php if ($message): ?>
            <div class="message <?php echo (strpos($message, 'Error') === false) ? 'success' : 'error'; ?>">
                <?php if (strpos($message, 'Error') === false): ?>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z" fill="currentColor"/>
                    </svg>
                <?php else: ?>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM13 17H11V15H13V17ZM13 13H11V7H13V13Z" fill="currentColor"/>
                    </svg>
                <?php endif; ?>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="adminAssign_task.php">
            <div class="form-group">
                <label for="volunteer_id" class="required">Select Volunteer</label>
                <select name="volunteer_id" id="volunteer_id" required>
                    <option value="">-- Select a volunteer --</option>
                    <?php foreach ($volunteers as $vol): ?>
                        <option value="<?php echo $vol['id']; ?>" <?php echo (isset($_POST['volunteer_id']) && $_POST['volunteer_id'] == $vol['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($vol['fullname'] . " ({$vol['username']})"); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="form-hint">Choose the volunteer you want to assign this task to</p>
            </div>
            
            <div class="form-group">
                <label for="task_title" class="required">Task Title</label>
                <input type="text" name="task_title" id="task_title" required 
                       value="<?php echo isset($_POST['task_title']) ? htmlspecialchars($_POST['task_title']) : ''; ?>"
                       placeholder="Enter a clear task title">
            </div>
            
            <div class="form-group">
                <label for="task_description">Task Description</label>
                <textarea name="task_description" id="task_description" 
                          placeholder="Provide details about the task, expectations, and any specific instructions"><?php echo isset($_POST['task_description']) ? htmlspecialchars($_POST['task_description']) : ''; ?></textarea>
                <p class="form-hint">Be as detailed as possible to help the volunteer understand the task</p>
            </div>
            
            <div class="form-group">
                <label for="due_date" class="required">Due Date</label>
                <input type="date" name="due_date" id="due_date" required 
                       value="<?php echo isset($_POST['due_date']) ? htmlspecialchars($_POST['due_date']) : ''; ?>"
                       min="<?php echo date('Y-m-d'); ?>">
                <p class="form-hint">Select the deadline for this task</p>
            </div>
            
            <button type="submit" class="btn">Assign Task</button>
        </form>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="admin_dashboard.php" class="back-link">← Back to Dashboard</a>
        </div>
    </div>

    <script>
        // Set minimum date to today
        document.getElementById('due_date').min = new Date().toISOString().split('T')[0];
        
        // Add some basic form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            let isValid = true;
            const volunteerSelect = document.getElementById('volunteer_id');
            const taskTitle = document.getElementById('task_title');
            const dueDate = document.getElementById('due_date');
            
            if (!volunteerSelect.value) {
                isValid = false;
                volunteerSelect.style.borderColor = '#e74c3c';
            } else {
                volunteerSelect.style.borderColor = '#ddd';
            }
            
            if (!taskTitle.value.trim()) {
                isValid = false;
                taskTitle.style.borderColor = '#e74c3c';
            } else {
                taskTitle.style.borderColor = '#ddd';
            }
            
            if (!dueDate.value) {
                isValid = false;
                dueDate.style.borderColor = '#e74c3c';
            } else {
                dueDate.style.borderColor = '#ddd';
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    </script>
</body>
</html>