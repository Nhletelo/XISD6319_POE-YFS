<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Database connection
try {
    require_once 'config/db_conn.php';
    
    // Test connection
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

$admin_id = $_SESSION['user_id'];
$admin_name = $_SESSION['fullname'] ?? 'Admin';

// Check if we're in edit mode for a specific article
$edit_mode = false;
$editing_article = null;

// Handle Add News
if (isset($_POST['add_news'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    try {
        $stmt = $conn->prepare("INSERT INTO news (title, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $content);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "News article added successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            throw new Exception("Error adding news: " . $conn->error);
        }
        $stmt->close();
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['message_type'] = "error";
    }
    
    header("Location: manage_news.php");
    exit;
}

// Handle Edit News - Update existing article
if (isset($_POST['update_news'])) {
    $id = $_POST['id'];
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    try {
        $stmt = $conn->prepare("UPDATE news SET title = ?, content = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $content, $id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "News article updated successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            throw new Exception("Error updating news: " . $conn->error);
        }
        $stmt->close();
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['message_type'] = "error";
    }
    
    header("Location: manage_news.php");
    exit;
}

// Handle Edit Request - Display article in edit form
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    
    try {
        $stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $editing_article = $result->fetch_assoc();
            $edit_mode = true;
        } else {
            $_SESSION['message'] = "News article not found!";
            $_SESSION['message_type'] = "error";
        }
        $stmt->close();
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['message_type'] = "error";
    }
}

// Handle Cancel Edit
if (isset($_GET['cancel_edit'])) {
    $edit_mode = false;
    $editing_article = null;
    header("Location: manage_news.php");
    exit;
}

// Handle Delete News
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    try {
        if ($conn->query("DELETE FROM news WHERE id = $id")) {
            $_SESSION['message'] = "News article deleted successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            throw new Exception("Error deleting news: " . $conn->error);
        }
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['message_type'] = "error";
    }
    
    header("Location: manage_news.php");
    exit;
}

// Fetch all news
try {
    $news_result = $conn->query("SELECT * FROM news ORDER BY created_at DESC");
    
    if (!$news_result) {
        throw new Exception("Error fetching news: " . $conn->error);
    }
    
    $news = [];
    while ($row = $news_result->fetch_assoc()) {
        $news[] = $row;
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
    <title>Manage News - Youth For Survival</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
 
   <link rel="stylesheet" href="css/manage_news.css">
</head>
<body>
    <div class="container">
        <h1>Manage News - Youth For Survival</h1>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message <?php echo $_SESSION['message_type'] === 'success' ? 'success' : 'error'; ?>">
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
        
        <!-- Add/Edit News Form -->
        <?php if ($edit_mode && $editing_article): ?>
            <!-- Edit Form -->
            <form action="manage_news.php" method="post" class="edit-form">
                <h2>Edit News Article</h2>
                
                <input type="hidden" name="id" value="<?php echo $editing_article['id']; ?>">
                
                <div class="form-group">
                    <label for="edit_title">News Title</label>
                    <input type="text" id="edit_title" name="title" value="<?php echo htmlspecialchars($editing_article['title']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_content">News Content</label>
                    <textarea id="edit_content" name="content" required><?php echo htmlspecialchars($editing_article['content']); ?></textarea>
                </div>
                
                <div class="form-actions">
                    <a href="manage_news.php?cancel_edit=true" class="btn btn-cancel">Cancel</a>
                    <button type="submit" name="update_news" class="btn btn-update">Update News</button>
                </div>
            </form>
        <?php else: ?>
            <!-- Add Form -->
            <form action="manage_news.php" method="post">
                <h2>Add New News Article</h2>
                
                <div class="form-group">
                    <label for="title">News Title</label>
                    <input type="text" id="title" name="title" placeholder="Enter news title" required>
                </div>
                
                <div class="form-group">
                    <label for="content">News Content</label>
                    <textarea id="content" name="content" placeholder="Enter news content" required></textarea>
                </div>
                
                <button type="submit" name="add_news">Add News</button>
            </form>
        <?php endif; ?>
        
        <!-- News List -->
        <h2>Existing News Articles</h2>
        
        <?php if (empty($news)): ?>
            <div class="empty-state">
                <i class="fas fa-newspaper"></i>
                <h3>No news articles found</h3>
                <p>There are no news articles in the database yet.</p>
            </div>
        <?php else: ?>
            <?php foreach ($news as $item): ?>
                <div class="news-item">
                    <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                    
                    <div class="news-meta">
                        <i class="far fa-calendar"></i> 
                        <?php echo date('F j, Y, g:i a', strtotime($item['created_at'])); ?>
                    </div>
                    
                    <div class="news-content">
                        <?php echo nl2br(htmlspecialchars($item['content'])); ?>
                    </div>
                    
                    <div class="news-actions">
                        <!-- Edit Button -->
                        <a href="manage_news.php?edit=<?php echo $item['id']; ?>" class="btn btn-edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        
                        <!-- Delete Button -->
                        <a href="manage_news.php?delete=<?php echo $item['id']; ?>" class="btn btn-delete" 
                           onclick="return confirm('Are you sure you want to delete this news article?')">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>