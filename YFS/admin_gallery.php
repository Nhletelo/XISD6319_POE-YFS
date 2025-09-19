<?php
session_start();
require_once 'config/db_conn.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle image upload
if (isset($_POST['submit'])) {
    if (!empty($_FILES['image']['name'])) {
        $caption = $conn->real_escape_string($_POST['caption']);
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $filename = basename($_FILES['image']['name']);
        $targetFile = $targetDir . time() . "_" . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $stmt = $conn->prepare("INSERT INTO gallery (image_path, caption, created_at) VALUES (?, ?, NOW())");
            $stmt->bind_param("ss", $targetFile, $caption);
            $stmt->execute();
            $stmt->close();
            header("Location: admin_gallery.php?success=uploaded");
            exit;
        } else {
            $error = "Failed to upload image.";
        }
    } else {
        $error = "Please select an image to upload.";
    }
}

// Handle delete action
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $res = $conn->query("SELECT image_path FROM gallery WHERE id = $id");
    if ($res && $res->num_rows > 0) {
        $img = $res->fetch_assoc();
        $file_path = $img['image_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        $conn->query("DELETE FROM gallery WHERE id = $id");
        header("Location: admin_gallery.php?success=deleted");
        exit;
    }
}

// Fetch all images
$images = $conn->query("SELECT * FROM gallery ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gallery - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Link to the CSS file -->
      <link rel="stylesheet" href="css/admin_gallery.css"

      <!-- Link JS with defer -->
      <script defer src="js/admin_gallery.js"></script>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="page-title"><i class="fas fa-images"></i> Manage Gallery</h1>
            <a href="admin_dashboard.php" class="btn">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        
        <?php if (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
            <div class="notification success">
                <i class="fas fa-check-circle"></i> Image deleted successfully!
            </div>
        <?php elseif (isset($_GET['success']) && $_GET['success'] === 'uploaded'): ?>
            <div class="notification success">
                <i class="fas fa-check-circle"></i> Image uploaded successfully!
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="notification error">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <div class="upload-card">
            <h3><i class="fas fa-upload"></i> Upload New Image</h3>
            <form method="post" enctype="multipart/form-data" id="uploadForm">
                <div class="form-group">
                    <label for="image">Select Image</label>
                    <input type="file" class="form-control" name="image" id="image" required>
                </div>
                <div class="form-group">
                    <label for="caption">Caption (Optional)</label>
                    <input type="text" class="form-control" name="caption" id="caption" placeholder="Enter a caption for the image">
                </div>
                <button type="submit" name="submit" class="btn btn-primary">
                    <i class="fas fa-cloud-upload-alt"></i> Upload Image
                </button>
            </form>
        </div>
        
        <?php if ($images->num_rows > 0): ?>
            <h2 style="margin-bottom: 20px;">Gallery Images (<?= $images->num_rows ?>)</h2>
            <div class="gallery-grid">
                <?php while ($row = $images->fetch_assoc()): ?>
                    <div class="gallery-card">
                        <img src="<?= htmlspecialchars($row['image_path']) ?>" alt="Gallery Image" class="gallery-img">
                        <div class="gallery-details">
                            <p class="gallery-id">ID: <?= $row['id'] ?></p>
                            <p class="gallery-caption"><?= !empty($row['caption']) ? htmlspecialchars($row['caption']) : '<span style="color:#6c757d; font-style:italic;">No caption</span>' ?></p>
                            <p class="gallery-date"><i class="far fa-calendar"></i> <?= date('M j, Y g:i A', strtotime($row['created_at'])) ?></p>
                        </div>
                        <div class="gallery-actions">
                            <a href="#" class="btn btn-danger" onclick="confirmDelete(<?= $row['id'] ?>)">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="far fa-folder-open"></i>
                <p>No images found in the gallery</p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Confirm Delete</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this image? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" onclick="closeModal()">Cancel</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>

</body>
</html>