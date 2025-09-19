<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Inbox - Youth For Survival</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --gray-color: #95a5a6;
            --light-gray: #bdc3c7;
            --sidebar-width: 280px;
            --header-height: 80px;
            --transition-speed: 0.3s;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7f9 0%, #e2e8f0 100%);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Header */
        .header {
            background: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-radius: 12px;
            margin-bottom: 25px;
            position: sticky;
            top: 10px;
            z-index: 100;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 24px;
            font-weight: bold;
        }
        
        .logo img {
            height: 50px;
            width: auto;
            transition: transform 0.3s ease;
        }
        
        .logo:hover img {
            transform: rotate(5deg);
        }
        
        .admin-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .admin-name {
            font-weight: 500;
            color: var(--primary-color);
            position: relative;
            padding-right: 15px;
        }
        
        .admin-name:after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            height: 20px;
            width: 2px;
            background: var(--light-gray);
        }
        
        /* Inbox Layout */
        .inbox-container {
            display: flex;
            gap: 25px;
        }
        
        /* Sidebar */
        .inbox-sidebar {
            width: var(--sidebar-width);
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            height: fit-content;
            position: sticky;
            top: calc(var(--header-height) + 20px);
        }
        
        .sidebar-header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .sidebar-header h2 {
            color: var(--primary-color);
            font-size: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .sidebar-header h2 i {
            color: var(--secondary-color);
        }
        
        .filters {
            margin-bottom: 25px;
        }
        
        .filters h3 {
            font-size: 16px;
            margin-bottom: 15px;
            color: var(--dark-color);
            padding-left: 5px;
        }
        
        .filter-list {
            list-style: none;
        }
        
        .filter-list li {
            margin-bottom: 8px;
        }
        
        .filter-list a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            text-decoration: none;
            color: var(--dark-color);
            border-radius: 8px;
            transition: all var(--transition-speed);
            position: relative;
            overflow: hidden;
        }
        
        .filter-list a:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--secondary-color);
            opacity: 0;
            transition: opacity var(--transition-speed);
        }
        
        .filter-list a:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }
        
        .filter-list a:hover:before {
            opacity: 1;
        }
        
        .filter-list a.active {
            background-color: var(--secondary-color);
            color: white;
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
        }
        
        .filter-list a.active:before {
            opacity: 1;
            background: white;
        }
        
        .filter-list i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            transition: transform var(--transition-speed);
        }
        
        .filter-list a:hover i {
            transform: scale(1.2);
        }
        
        .filter-list .badge {
            margin-left: auto;
            background-color: var(--accent-color);
            color: white;
            border-radius: 20px;
            padding: 3px 10px;
            font-size: 12px;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(231, 76, 60, 0.2);
        }
        
        .sidebar-btn {
            display: block;
            width: 100%;
            padding: 12px;
            text-align: center;
            background: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-speed);
            text-decoration: none;
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
        }
        
        .sidebar-btn:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(52, 152, 219, 0.4);
        }
        
        .sidebar-btn i {
            margin-right: 8px;
        }
        
        /* Main Content */
        .inbox-main {
            flex: 1;
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        
        .inbox-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .inbox-header h1 {
            color: var(--primary-color);
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .inbox-header h1 i {
            color: var(--secondary-color);
            font-size: 24px;
        }
        
        .inbox-actions {
            display: flex;
            gap: 12px;
        }
        
        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            text-decoration: none;
            font-size: 14px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .btn-primary {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
        }
        
        .btn-success {
            background-color: var(--success-color);
            color: white;
        }
        
        .btn-success:hover {
            background-color: #219653;
        }
        
        .btn-danger {
            background-color: var(--accent-color);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
        }
        
        /* Search Box */
        .search-box {
            display: flex;
            margin-bottom: 25px;
            gap: 12px;
            position: relative;
        }
        
        .search-input {
            flex: 1;
            padding: 12px 20px;
            border: 1px solid var(--light-gray);
            border-radius: 8px;
            font-size: 15px;
            transition: all var(--transition-speed);
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 2px 8px rgba(52, 152, 219, 0.2);
        }
        
        .search-btn {
            padding: 12px 20px;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all var(--transition-speed);
            box-shadow: 0 2px 6px rgba(52, 152, 219, 0.2);
        }
        
        .search-btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
        }
        
        /* Messages Table */
        .messages-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .messages-table th,
        .messages-table td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .messages-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: var(--dark-color);
            position: sticky;
            top: 0;
        }
        
        .messages-table tr {
            transition: background-color 0.2s;
        }
        
        .messages-table tr:hover {
            background-color: #f8f9fa;
        }
        
        .messages-table tr.unread {
            background-color: #e8f4ff;
            font-weight: 600;
        }
        
        .messages-table tr.unread:hover {
            background-color: #dbefff;
        }
        
        .message-subject {
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .message-preview {
            color: var(--gray-color);
            font-size: 0.9rem;
            margin-top: 6px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .message-sender, .message-receiver {
            white-space: nowrap;
        }
        
        .message-time {
            white-space: nowrap;
            color: var(--gray-color);
            font-size: 0.9rem;
        }
        
        .message-actions {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
        }
        
        .btn-view {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-view:hover {
            background-color: #2980b9;
        }
        
        .btn-reply {
            background-color: var(--success-color);
            color: white;
        }
        
        .btn-reply:hover {
            background-color: #219653;
        }
        
        .btn-delete {
            background-color: var(--accent-color);
            color: white;
        }
        
        .btn-delete:hover {
            background-color: #c0392b;
        }
        
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
        }
        
        .modal-content {
            background-color: white;
            padding: 35px;
            border-radius: 12px;
            width: 90%;
            max-width: 700px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            animation: modalFadeIn 0.3s ease;
        }
        
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .modal-title {
            font-size: 24px;
            color: var(--primary-color);
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 1.8rem;
            cursor: pointer;
            color: var(--gray-color);
            transition: color 0.2s;
        }
        
        .close-modal:hover {
            color: var(--accent-color);
        }
        
        .message-details {
            margin-bottom: 25px;
        }
        
        .message-detail {
            display: flex;
            margin-bottom: 12px;
        }
        
        .detail-label {
            font-weight: 600;
            width: 80px;
            color: var(--dark-color);
        }
        
        .message-body {
            margin-top: 25px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid var(--secondary-color);
            white-space: pre-wrap;
            line-height: 1.6;
        }
        
        .modal-actions {
            display: flex;
            gap: 12px;
            margin-top: 25px;
            justify-content: flex-end;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray-color);
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: var(--light-gray);
        }
        
        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .inbox-container {
                flex-direction: column;
            }
            
            .inbox-sidebar {
                width: 100%;
                position: static;
                margin-bottom: 20px;
            }
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .admin-name:after {
                display: none;
            }
            
            .inbox-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .inbox-actions {
                width: 100%;
                justify-content: space-between;
            }
            
            .messages-table {
                display: block;
                overflow-x: auto;
            }
            
            .search-box {
                flex-direction: column;
            }
            
            .modal-content {
                padding: 25px 20px;
            }
            
            .modal-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
        
        /* Animation for new messages */
        @keyframes highlight {
            0% { background-color: rgba(52, 152, 219, 0.3); }
            100% { background-color: transparent; }
        }
        
        .highlight {
            animation: highlight 2s ease;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--secondary-color);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Header -->
    <div class="header">
        <div class="logo">
            <img src="https://via.placeholder.com/50x50/3498db/ffffff?text=YFS" alt="Youth For Survival Logo">
            <span>YouthFor<span style="color: var(--accent-color);">Survival</span></span>
        </div>
        <div class="admin-info">
            <span class="admin-name">Welcome, Admin User</span>
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
                <h2><i class="fas fa-inbox"></i> Message Center</h2>
            </div>
            
            <div class="filters">
                <h3>Filters</h3>
                <ul class="filter-list">
                    <li>
                        <a href="admin_inbox.php?filter=all" class="active">
                            <i class="fas fa-inbox"></i> All Messages
                            <span class="badge">68</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_inbox.php?filter=unread">
                            <i class="fas fa-envelope"></i> Unread
                            <span class="badge">15</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_inbox.php?filter=read">
                            <i class="fas fa-envelope-open"></i> Read
                            <span class="badge">53</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_inbox.php?filter=sent_to_admin">
                            <i class="fas fa-user-shield"></i> To Admin
                            <span class="badge">42</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_inbox.php?filter=sent_by_admin">
                            <i class="fas fa-paper-plane"></i> From Admin
                            <span class="badge">26</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <a href="compose.php" class="sidebar-btn">
                <i class="fas fa-edit"></i> Compose New Message
            </a>
        </div>
        
        <!-- Main Content -->
        <div class="inbox-main">
            <div class="inbox-header">
                <h1><i class="fas fa-inbox"></i> Admin Inbox</h1>
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
                <input type="hidden" name="filter" value="all">
                <input type="text" name="search" class="search-input" placeholder="Search messages...">
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="admin_inbox.php?filter=all" class="btn btn-danger">
                    <i class="fas fa-times"></i> Clear
                </a>
            </form>
            
            <!-- Messages Table -->
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
                    <tr class="unread highlight">
                        <td class="message-sender">John Volunteer</td>
                        <td class="message-receiver">Admin User</td>
                        <td>
                            <div class="message-subject">Question about upcoming event</div>
                            <div class="message-preview">
                                Hello, I have a question about my assignment for the community cleanup event this weekend...
                            </div>
                        </td>
                        <td class="message-time">Today, 10:45 AM</td>
                        <td>
                            <div class="message-actions">
                                <button class="action-btn btn-view">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="action-btn btn-reply">
                                    <i class="fas fa-reply"></i> Reply
                                </button>
                                <button class="action-btn btn-delete">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="unread">
                        <td class="message-sender">Sarah Coordinator</td>
                        <td class="message-receiver">Admin User</td>
                        <td>
                            <div class="message-subject">Volunteer Statistics Report</div>
                            <div class="message-preview">
                                Here's the monthly volunteer report you requested. We've seen a 15% increase in...
                            </div>
                        </td>
                        <td class="message-time">Today, 9:30 AM</td>
                        <td>
                            <div class="message-actions">
                                <button class="action-btn btn-view">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="action-btn btn-reply">
                                    <i class="fas fa-reply"></i> Reply
                                </button>
                                <button class="action-btn btn-delete">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="message-sender">Admin User</td>
                        <td class="message-receiver">All Volunteers</td>
                        <td>
                            <div class="message-subject">Important Announcement: Schedule Change</div>
                            <div class="message-preview">
                                Please note that the training session scheduled for Friday has been moved to...
                            </div>
                        </td>
                        <td class="message-time">Yesterday, 3:15 PM</td>
                        <td>
                            <div class="message-actions">
                                <button class="action-btn btn-view">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="action-btn btn-reply">
                                    <i class="fas fa-reply"></i> Reply
                                </button>
                                <button class="action-btn btn-delete">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="message-sender">Michael Thompson</td>
                        <td class="message-receiver">Admin User</td>
                        <td>
                            <div class="message-subject">Application Approval</div>
                            <div class="message-preview">
                                Thank you for approving my volunteer application! I'm excited to join the team...
                            </div>
                        </td>
                        <td class="message-time">Oct 15, 4:20 PM</td>
                        <td>
                            <div class="message-actions">
                                <button class="action-btn btn-view">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="action-btn btn-reply">
                                    <i class="fas fa-reply"></i> Reply
                                </button>
                                <button class="action-btn btn-delete">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="message-sender">Admin User</td>
                        <td class="message-receiver">Event Team Leads</td>
                        <td>
                            <div class="message-subject">Meeting Reminder</div>
                            <div class="message-preview">
                                This is a reminder about our monthly team leads meeting tomorrow at 10 AM in...
                            </div>
                        </td>
                        <td class="message-time">Oct 14, 11:30 AM</td>
                        <td>
                            <div class="message-actions">
                                <button class="action-btn btn-view">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="action-btn btn-reply">
                                    <i class="fas fa-reply"></i> Reply
                                </button>
                                <button class="action-btn btn-delete">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Message View Modal -->
<div class="modal" id="messageModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Question about upcoming event</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="message-details">
            <div class="message-detail">
                <span class="detail-label">From:</span>
                <span>John Volunteer (john.volunteer@example.com)</span>
            </div>
            <div class="message-detail">
                <span class="detail-label">To:</span>
                <span>Admin User (admin@youthforsurvival.org)</span>
            </div>
            <div class="message-detail">
                <span class="detail-label">Date:</span>
                <span>Today, 10:45 AM</span>
            </div>
        </div>
        <div class="message-body">
            Hello Admin,

I have a question about my assignment for the community cleanup event this weekend. The schedule shows me working from 9 AM to 2 PM, but I noticed that I'm also signed up for the afternoon shift at the donation center.

Could you please clarify if this is correct, or should I be at one location for the entire day?

Also, will there be parking available near the cleanup site? I'm planning to drive to the location.

Thank you for your help!

Best regards,
John Volunteer
        </div>
        <div class="modal-actions">
            <button class="btn btn-primary">
                <i class="fas fa-reply"></i> Reply
            </button>
            <button class="btn btn-success">
                <i class="fas fa-check"></i> Mark as Read
            </button>
            <button class="btn btn-danger">
                <i class="fas fa-trash"></i> Delete
            </button>
            <button class="btn">
                <i class="fas fa-times"></i> Close
            </button>
        </div>
    </div>
</div>

<script>
    // Simple demo functionality
    document.addEventListener('DOMContentLoaded', function() {
        const viewButtons = document.querySelectorAll('.btn-view');
        const modal = document.getElementById('messageModal');
        const closeModal = document.querySelector('.close-modal');
        
        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                modal.style.display = 'flex';
            });
        });
        
        closeModal.addEventListener('click', function() {
            modal.style.display = 'none';
        });
        
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
        
        // Mark as read functionality
        const markAsReadBtn = document.querySelector('.btn-success');
        markAsReadBtn.addEventListener('click', function() {
            const row = document.querySelector('.highlight');
            if (row) {
                row.classList.remove('unread', 'highlight');
                // In a real application, you would send an AJAX request to update the database
                alert('Message marked as read!');
                modal.style.display = 'none';
            }
        });
    });
</script>
</body>
</html>