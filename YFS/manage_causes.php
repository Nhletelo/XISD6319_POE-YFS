<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Database connection
try {
    $conn = new mysqli('localhost', 'root', '', 'youth_for_survival');
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

$admin_id = $_SESSION['user_id'];
$admin_name = $_SESSION['fullname'] ?? 'Admin';

// Handle Add Cause
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_cause'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    try {
        $stmt = $conn->prepare("INSERT INTO causes (title, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $description);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Cause added successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            throw new Exception("Error adding cause: " . $conn->error);
        }
        $stmt->close();
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['message_type'] = "error";
    }
    
    header("Location: manage_causes.php");
    exit;
}

// Check if we're in edit mode
$edit_mode = false;
$editing_cause = null;

// Handle Edit Request
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    
    try {
        $stmt = $conn->prepare("SELECT * FROM causes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $editing_cause = $result->fetch_assoc();
            $edit_mode = true;
        } else {
            $_SESSION['message'] = "Cause not found!";
            $_SESSION['message_type'] = "error";
        }
        $stmt->close();
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['message_type'] = "error";
    }
}

// Handle Update Cause
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cause'])) {
    $id = $_POST['id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    try {
        $stmt = $conn->prepare("UPDATE causes SET title = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $description, $id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Cause updated successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            throw new Exception("Error updating cause: " . $conn->error);
        }
        $stmt->close();
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['message_type'] = "error";
    }
    
    header("Location: manage_causes.php");
    exit;
}

// Handle Cancel Edit
if (isset($_GET['cancel_edit'])) {
    $edit_mode = false;
    $editing_cause = null;
    header("Location: manage_causes.php");
    exit;
}

// Handle Delete Cause
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    try {
        if ($conn->query("DELETE FROM causes WHERE id = $id")) {
            $_SESSION['message'] = "Cause deleted successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            throw new Exception("Error deleting cause: " . $conn->error);
        }
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['message_type'] = "error";
    }
    
    header("Location: manage_causes.php");
    exit;
}

// Fetch all causes
try {
    $causes_result = $conn->query("SELECT * FROM causes ORDER BY created_at DESC");
    
    if (!$causes_result) {
        throw new Exception("Error fetching causes: " . $conn->error);
    }
    
    $causes = [];
    while ($row = $causes_result->fetch_assoc()) {
        $causes[] = $row;
    }
} catch (Exception $e) {
    $error_message = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Causes - Youth For Survival</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/manage_causes.css">
    
        
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Manage Causes - Youth For Survival</h1>
            <div class="admin-info">
                <span>Welcome, <?php echo htmlspecialchars($admin_name); ?></span>
                <a href="admin_dashboard.php" class="btn btn-primary">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            
            </div>
        </div>
        
        <!-- Message Alerts -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message <?php echo $_SESSION['message_type'] === 'success' ? 'success' : 'error'; ?>">
                <i class="<?php echo $_SESSION['message_type'] == 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'; ?>"></i>
                <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']);
                    unset($_SESSION['message_type']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="message error">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <!-- Add/Edit Cause Form -->
        <?php if ($edit_mode && $editing_cause): ?>
            <!-- Edit Form -->
            <div class="form-container edit-form">
                <h2 class="form-title"><i class="fas fa-edit"></i> Edit Cause</h2>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $editing_cause['id']; ?>">
                    
                    <div class="form-group">
                        <label for="edit_title">Cause Title</label>
                        <input type="text" id="edit_title" name="title" value="<?php echo htmlspecialchars($editing_cause['title']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_description">Cause Description</label>
                        <textarea id="edit_description" name="description" required><?php echo htmlspecialchars($editing_cause['description']); ?></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <a href="manage_causes.php?cancel_edit=true" class="btn btn-warning">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" name="update_cause" class="btn btn-success">
                            <i class="fas fa-save"></i> Update Cause
                        </button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <!-- Add Form -->
            <div class="form-container">
                <h2 class="form-title"><i class="fas fa-plus"></i> Add New Cause</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="title">Cause Title</label>
                        <input type="text" id="title" name="title" placeholder="Enter cause title" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Cause Description</label>
                        <textarea id="description" name="description" placeholder="Enter cause description" required></textarea>
                    </div>
                    
                    <button type="submit" name="add_cause" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Add Cause
                    </button>
                </form>
            </div>
        <?php endif; ?>
        
        <!-- Causes List -->
        <h2 style="margin-bottom: 20px;"><i class="fas fa-hand-holding-heart"></i> Existing Causes</h2>
        
        <?php if (empty($causes)): ?>
            <div class="empty-state">
                <i class="fas fa-hands-helping"></i>
                <h3>No causes found</h3>
                <p>There are no causes in the database yet. Add your first cause using the form above.</p>
            </div>
        <?php else: ?>
            <div class="causes-list">
                <?php foreach ($causes as $cause): ?>
                    <div class="cause-item">
                        <div class="cause-header">
                            <div>
                                <div class="cause-title"><?php echo htmlspecialchars($cause['title']); ?></div>
                                <div class="cause-meta">
                                    <i class="far fa-calendar"></i> 
                                    <?php echo date('F j, Y', strtotime($cause['created_at'])); ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="cause-description">
                            <?php echo nl2br(htmlspecialchars($cause['description'])); ?>
                        </div>
                        
                        <div class="cause-actions">
                            <!-- Edit Button -->
                            <a href="manage_causes.php?edit=<?php echo $cause['id']; ?>" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            
                            <!-- Delete Button -->
                            <a href="manage_causes.php?delete=<?php echo $cause['id']; ?>" class="btn btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this cause? This action cannot be undone.')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>