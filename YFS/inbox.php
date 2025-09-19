<?php
session_start();
require_once 'config/db_conn.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$admin_id = $_SESSION['user_id'];
$admin_name = $_SESSION['fullname'] ?? 'Admin';

// Handle message actions (mark as read, delete, reply)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mark_as_read']) && isset($_POST['message_id'])) {
        $message_id = $_POST['message_id'];
        $stmt = $conn->prepare("UPDATE messages SET is_read = 1 WHERE id = ?");
        $stmt->bind_param("i", $message_id);
        $stmt->execute();
        $stmt->close();
        header("Location: inbox.php");
        exit;
    }
    
    if (isset($_POST['delete_message']) && isset($_POST['message_id'])) {
        $message_id = $_POST['message_id'];
        $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
        $stmt->bind_param("i", $message_id);
        $stmt->execute();
        $stmt->close();
        header("Location: inbox.php");
        exit;
    }
    
    if (isset($_POST['mark_all_read'])) {
        $stmt = $conn->prepare("UPDATE messages SET is_read = 1");
        $stmt->execute();
        $stmt->close();
        header("Location: inbox.php");
        exit;
    }
}

// Get filter parameters
$filter = $_GET['filter'] ?? 'all';
$search = $_GET['search'] ?? '';

// Build query based on filters
$query = "
    SELECT m.id, m.subject, m.body, m.sent_at, m.is_read, 
           u_sender.fullname AS sender_name, u_sender.username AS sender_username,
           u_receiver.fullname AS receiver_name, u_receiver.username AS receiver_username
    FROM messages m
    JOIN app_users u_sender ON m.sender_id = u_sender.id
    JOIN app_users u_receiver ON m.receiver_id = u_receiver.id
";

$params = [];
$types = "";

if (!empty($search)) {
    $query .= " WHERE (m.subject LIKE ? OR m.body LIKE ? OR u_sender.fullname LIKE ? OR u_receiver.fullname LIKE ?)";
    $search_term = "%$search%";
    $params = array_fill(0, 4, $search_term);
    $types = "ssss";
} elseif ($filter !== 'all') {
    $query .= " WHERE ";
    switch ($filter) {
        case 'unread':
            $query .= "m.is_read = 0";
            break;
        case 'read':
            $query .= "m.is_read = 1";
            break;
        case 'sent_to_admin':
            $query .= "m.receiver_id = ?";
            $params = [$admin_id];
            $types = "i";
            break;
        case 'sent_by_admin':
            $query .= "m.sender_id = ?";
            $params = [$admin_id];
            $types = "i";
            break;
    }
}

$query .= " ORDER BY m.sent_at DESC";

// Prepare and execute query
$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

$stmt->close();

// Get counts for filters
$all_count = $conn->query("SELECT COUNT(*) as count FROM messages")->fetch_assoc()['count'];
$unread_count = $conn->query("SELECT COUNT(*) as count FROM messages WHERE is_read = 0")->fetch_assoc()['count'];
$read_count = $conn->query("SELECT COUNT(*) as count FROM messages WHERE is_read = 1")->fetch_assoc()['count'];
$to_admin_count = $conn->query("SELECT COUNT(*) as count FROM messages WHERE receiver_id = $admin_id")->fetch_assoc()['count'];
$from_admin_count = $conn->query("SELECT COUNT(*) as count FROM messages WHERE sender_id = $admin_id")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Inbox - Youth For Survival</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/inbox.css">
</head>
<body>

<div class="container">
    <!-- Header -->
    <div class="header">
        <div class="logo">
            <img src="images/logo_large.webp" alt="Youth For Survival Logo">
            <span>YouthFor<span style="color: var(--accent-color);">Survival</span></span>
        </div>
        <div class="admin-info">
            <span class="admin-name">Welcome, <?php echo htmlspecialchars($admin_name); ?></span>
            <a href="admin_dashboard.php" class="btn btn-primary">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="logout.php" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
    
    <div class="inbox-container">
        <!-- Sidebar -->
        <div class="inbox-sidebar">
            <div class="sidebar-header">
                <h2>Message Center</h2>
            </div>
            
            <div class="filters">
                <h3>Filters</h3>
                <ul class="filter-list">
                    <li>
                        <a href="inbox.php?filter=all" class="<?php echo $filter === 'all' ? 'active' : ''; ?>">
                            <i class="fas fa-inbox"></i> All Messages
                            <span class="badge"><?php echo $all_count; ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="inbox.php?filter=unread" class="<?php echo $filter === 'unread' ? 'active' : ''; ?>">
                            <i class="fas fa-envelope"></i> Unread
                            <span class="badge"><?php echo $unread_count; ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="inbox.php?filter=read" class="<?php echo $filter === 'read' ? 'active' : ''; ?>">
                            <i class="fas fa-envelope-open"></i> Read
                            <span class="badge"><?php echo $read_count; ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="inbox.php?filter=sent_to_admin" class="<?php echo $filter === 'sent_to_admin' ? 'active' : ''; ?>">
                            <i class="fas fa-user-shield"></i> To Admin
                            <span class="badge"><?php echo $to_admin_count; ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="inbox.php?filter=sent_by_admin" class="<?php echo $filter === 'sent_by_admin' ? 'active' : ''; ?>">
                            <i class="fas fa-paper-plane"></i> From Admin
                            <span class="badge"><?php echo $from_admin_count; ?></span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <a href="compose.php" class="btn btn-primary" style="width: 100%;">
                <i class="fas fa-edit"></i> Compose New Message
            </a>
        </div>
        
        <!-- Main Content -->
        <div class="inbox-main">
            <div class="inbox-header">
                <h1>Message Inbox</h1>
                <div class="inbox-actions">
                    <form method="POST" style="display: inline;">
                        <button type="submit" name="mark_all_read" class="btn btn-success">
                            <i class="fas fa-check-double"></i> Mark All as Read
                        </button>
                    </form>
                    <a href="compose.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Message
                    </a>
                </div>
            </div>
            
            <!-- Search Box -->
            <form method="GET" class="search-box">
                <input type="hidden" name="filter" value="<?php echo $filter; ?>">
                <input type="text" name="search" class="search-input" placeholder="Search messages..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i> Search
                </button>
                <?php if (!empty($search)): ?>
                    <a href="admin_inbox.php?filter=<?php echo $filter; ?>" class="btn btn-danger">
                        <i class="fas fa-times"></i> Clear
                    </a>
                <?php endif; ?>
            </form>
            
            <?php if (empty($messages)): ?>
                <div class="empty-state">
                    <i class="far fa-envelope-open"></i>
                    <h3>No messages found</h3>
                    <p>There are no messages matching your criteria.</p>
                </div>
            <?php else: ?>
                <table class="messages-table">
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>To</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $msg): ?>
                        <tr class="<?php echo $msg['is_read'] ? '' : 'unread'; ?>">
                            <td class="message-sender"><?php echo htmlspecialchars($msg['sender_name']); ?></td>
                            <td class="message-receiver"><?php echo htmlspecialchars($msg['receiver_name']); ?></td>
                            <td>
                                <div class="message-subject"><?php echo htmlspecialchars($msg['subject']); ?></div>
                                <div class="message-preview">
                                    <?php echo htmlspecialchars(substr($msg['body'], 0, 60) . (strlen($msg['body']) > 60 ? '...' : '')); ?>
                                </div>
                            </td>
                            <td class="message-time"><?php echo date('M j, g:i A', strtotime($msg['sent_at'])); ?></td>
                            <td>
                                <div class="message-actions">
                                    <button class="action-btn btn-view" onclick="viewMessage(<?php echo $msg['id']; ?>)">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button class="action-btn btn-reply" onclick="replyMessage(<?php echo $msg['id']; ?>)">
                                        <i class="fas fa-reply"></i> Reply
                                    </button>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="message_id" value="<?php echo $msg['id']; ?>">
                                        <button type="submit" name="delete_message" class="action-btn btn-delete" onclick="return confirm('Are you sure you want to delete this message?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Message View Modal -->
<div class="modal" id="messageModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title" id="modalSubject">Message Subject</h2>
            <button class="close-modal" onclick="closeModal()">&times;</button>
        </div>
        <div class="message-details">
            <div class="message-detail">
                <span class="detail-label">From:</span>
                <span id="modalSender"></span>
            </div>
            <div class="message-detail">
                <span class="detail-label">To:</span>
                <span id="modalReceiver"></span>
            </div>
            <div class="message-detail">
                <span class="detail-label">Date:</span>
                <span id="modalDate"></span>
            </div>
        </div>
        <div class="message-body" id="modalBody"></div>
        <div class="modal-actions">
            <button class="btn btn-primary" id="modalReplyBtn">
                <i class="fas fa-reply"></i> Reply
            </button>
            <form method="POST" style="display: inline;">
                <input type="hidden" name="message_id" id="modalMessageId">
                <button type="submit" name="delete_message" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this message?')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
            <button class="btn" onclick="closeModal()">
                <i class="fas fa-times"></i> Close
            </button>
        </div>
    </div>
</div>

<script>
    // View message details
    function viewMessage(messageId) {
        // Fetch message details via AJAX
        fetch('get_message.php?id=' + messageId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('modalSubject').textContent = data.message.subject;
                    document.getElementById('modalSender').textContent = data.message.sender_name + ' (' + data.message.sender_username + ')';
                    document.getElementById('modalReceiver').textContent = data.message.receiver_name + ' (' + data.message.receiver_username + ')';
                    document.getElementById('modalDate').textContent = new Date(data.message.sent_at).toLocaleString();
                    document.getElementById('modalBody').textContent = data.message.body;
                    document.getElementById('modalMessageId').value = data.message.id;
                    document.getElementById('modalReplyBtn').onclick = function() {
                        window.location.href = 'compose.php?reply_to=' + data.message.id;
                    };
                    
                    // Show modal
                    document.getElementById('messageModal').style.display = 'flex';
                    
                    // Mark as read if unread
                    if (data.message.is_read === 0) {
                        fetch(window.location.href, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'mark_as_read=true&message_id=' + data.message.id
                        });
                    }
                } else {
                    alert('Error loading message: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading message details.');
            });
    }
    
    // Reply to message
    function replyMessage(messageId) {
        window.location.href = 'compose.php?reply_to=' + messageId;
    }
    
    // Close modal
    function closeModal() {
        document.getElementById('messageModal').style.display = 'none';
        // Reload page to update read status
        window.location.reload();
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('messageModal');
        if (event.target === modal) {
            closeModal();
        }
    };
</script>
</body>
</html>