<?php
session_start();
require_once 'config/db_conn.php';

// Delete donation if requested
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM donations WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_view_donations.php?message=deleted");
    exit;
}

// Handle export to CSV
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=donations.csv');

    $output = fopen("php://output", "w");
    fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'Amount', 'Payment Preference', 'Status', 'Donated At']);
    
    // Use prepared statement for security
    $export_query = $conn->prepare("SELECT * FROM donations ORDER BY donated_at DESC");
    $export_query->execute();
    $export_result = $export_query->get_result();
    
    while ($row = $export_result->fetch_assoc()) {
        fputcsv($output, [
            $row['id'], 
            $row['name'], 
            $row['email'], 
            $row['phone'], 
            $row['amount'], 
            $row['payment_preference'],
            $row['status'],
            $row['donated_at']
        ]);
    }
    
    fclose($output);
    exit;
}

// Handle search with prepared statement
$search = '';
$where = '';
$params = [];
$types = '';

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%';
    $where = "WHERE name LIKE ? OR email LIKE ?";
    $params = [$search, $search];
    $types = "ss";
}

// Fetch donations with prepared statement
$sql = "SELECT * FROM donations $where ORDER BY donated_at DESC";
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Donation Requests</title>
    <link rel="stylesheet" href="css/admin_view_donations.css">
</head>
<body>
    <div class="header">
        <h1>Donation Management</h1>
        <p>View and manage donation requests</p>
    </div>
    
    <?php if (isset($_GET['message']) && $_GET['message'] === 'deleted'): ?>
        <div class="message">Donation record deleted successfully!</div>
    <?php endif; ?>
    
    <div class="search-box">
        <form method="GET" action="" style="display: flex; flex-wrap: wrap; gap: 10px; width: 100%; justify-content: center;">
            <input type="text" class="search-input" name="search" placeholder="Search by name or email" value="<?= htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : '') ?>">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="admin_view_donations.php" class="btn btn-secondary">Reset</a>
            <a href="?export=csv" class="btn btn-success">Export CSV</a>
        </form>
    </div>
    
    <?php if ($result->num_rows > 0): ?>
        <table class="donations-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th class="hide-mobile">Phone</th>
                    <th>Amount</th>
                    <th class="hide-mobile">Payment Method</th>
                    <th>Status</th>
                    <th>Donation Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 1; ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $count++ ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td class="hide-mobile"><?= htmlspecialchars($row['phone'] ?? 'N/A') ?></td>
                        <td>R<?= number_format($row['amount'], 2) ?></td>
                        <td class="hide-mobile"><?= htmlspecialchars($row['payment_preference'] ?? 'N/A') ?></td>
                        <td>
                            <span class="status-<?= $row['status'] ?>">
                                <?= ucfirst($row['status']) ?>
                            </span>
                        </td>
                        <td><?= date('M j, Y', strtotime($row['donated_at'])) ?></td>
                        <td>
                            <a href="?delete_id=<?= $row['id'] ?>" class="action-btn" onclick="return confirm('Are you sure you want to delete this donation?');">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-data">
            <i>📭</i>
            <p>No donation requests found.</p>
        </div>
    <?php endif; ?>
    
    <div style="text-align: center;">
        <a href="admin_dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>

    <script>
        // Simple confirmation for delete actions
        document.querySelectorAll('.action-btn').forEach(link => {
            link.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to delete this donation?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>

<?php 
// Close the statement if it exists
if (isset($stmt)) {
    $stmt->close();
}
$conn->close(); 
?>