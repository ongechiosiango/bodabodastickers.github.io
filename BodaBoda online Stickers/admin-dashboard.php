<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

// Include database connection
include('db-conn.php');

// Get admin details
$admin_id = $_SESSION['admin_id'];
$sql = "SELECT * FROM admins WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Count total riders
$riders_sql = "SELECT COUNT(*) as total_riders FROM riders";
$riders_result = $conn->query($riders_sql);
$total_riders = $riders_result->fetch_assoc()['total_riders'];

// Count pending approvals (check if approved column exists first)
$pending_approvals = 0;
$check_column_sql = "SHOW COLUMNS FROM riders LIKE 'approved'";
$column_result = $conn->query($check_column_sql);
if ($column_result->num_rows > 0) {
    $pending_sql = "SELECT COUNT(*) as pending FROM riders WHERE approved = 0";
    $pending_result = $conn->query($pending_sql);
    $pending_approvals = $pending_result->fetch_assoc()['pending'];
}

// Count total payments
$payments_sql = "SELECT COUNT(*) as total_payments FROM payments";
$payments_result = $conn->query($payments_sql);
$total_payments = $payments_result->fetch_assoc()['total_payments'];

// Get recent activities
$recent_activities = [];
$check_table_sql = "SHOW TABLES LIKE 'admin_activities'";
$table_result = $conn->query($check_table_sql);
if ($table_result->num_rows > 0) {
    $check_date_column = "SHOW COLUMNS FROM admin_activities LIKE 'activity_date'";
    $date_column_result = $conn->query($check_date_column);
    if ($date_column_result->num_rows > 0) {
        $activity_sql = "SELECT * FROM admin_activities WHERE admin_id = ? ORDER BY activity_date DESC LIMIT 5";
        $activity_stmt = $conn->prepare($activity_sql);
        if ($activity_stmt) {
            $activity_stmt->bind_param("i", $admin_id);
            $activity_stmt->execute();
            $result = $activity_stmt->get_result();
            $recent_activities = $result->fetch_all(MYSQLI_ASSOC);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BODABODA ONLINE STICKERS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --info: #4895ef;
            --light: #f8f9fa;
            --dark: #212529;
            --white: #ffffff;
            --gray: #6c757d;
            --gray-light: #e9ecef;
            --border-radius: 0.375rem;
            --box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fb;
            color: var(--dark);
            line-height: 1.6;
        }
        
        .dashboard-container {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            background: linear-gradient(180deg, var(--primary), var(--primary-dark));
            color: var(--white);
            padding: 1.5rem 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            height: 100vh;
        }
        
        .sidebar-header {
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }
        
        .sidebar-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
            border: 3px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--box-shadow);
        }
        
        .avatar-initials {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
            font-weight: 600;
            border: 3px solid rgba(255, 255, 255, 0.2);
        }
        
        .sidebar-header h3 {
            margin: 0.5rem 0 0.25rem;
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .sidebar-header p {
            margin: 0;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
            background: rgba(255, 255, 255, 0.1);
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
            display: inline-block;
        }
        
        .sidebar-menu {
            padding: 1.5rem 0;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
            border-left: 3px solid transparent;
            margin: 0.25rem 0;
            font-size: 0.95rem;
        }
        
        .sidebar-menu a:hover {
            color: var(--white);
            background: rgba(255, 255, 255, 0.1);
            border-left: 3px solid var(--white);
        }
        
        .sidebar-menu a.active {
            color: var(--white);
            background: rgba(255, 255, 255, 0.2);
            border-left: 3px solid var(--white);
            font-weight: 500;
        }
        
        .sidebar-menu a i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        /* Main Content Styles */
        .main-content {
            padding: 2rem;
            overflow-y: auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--gray-light);
        }
        
        .header h1 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--primary);
        }
        
        .logout-btn {
            background-color: var(--danger);
            color: var(--white);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            font-weight: 500;
        }
        
        .logout-btn:hover {
            background-color: #d51a6a;
            transform: translateY(-2px);
        }
        
        .logout-btn i {
            margin-right: 0.5rem;
        }
        
        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--box-shadow);
            display: flex;
            align-items: center;
            transition: var(--transition);
            border-top: 4px solid transparent;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 1.5rem rgba(0, 0, 0, 0.1);
        }
        
        .stat-card.riders {
            border-top-color: var(--primary);
        }
        
        .stat-card.pending {
            border-top-color: var(--warning);
        }
        
        .stat-card.payments {
            border-top-color: var(--success);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.25rem;
            font-size: 1.5rem;
            color: var(--white);
        }
        
        .stat-icon.riders {
            background-color: var(--primary);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        .stat-icon.pending {
            background-color: var(--warning);
            box-shadow: 0 5px 15px rgba(248, 150, 30, 0.3);
        }
        
        .stat-icon.payments {
            background-color: var(--success);
            box-shadow: 0 5px 15px rgba(76, 201, 240, 0.3);
        }
        
        .stat-info h3 {
            margin: 0 0 0.25rem;
            font-size: 1rem;
            font-weight: 500;
            color: var(--gray);
        }
        
        .stat-info p {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark);
        }
        
        /* Recent Activity */
        .card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--box-shadow);
            margin-bottom: 2rem;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--gray-light);
        }
        
        .card-header h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
            display: flex;
            align-items: center;
        }
        
        .card-header h2 i {
            margin-right: 0.75rem;
        }
        
        .activity-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .activity-item {
            padding: 1rem 0;
            border-bottom: 1px solid var(--gray-light);
            display: flex;
            align-items: center;
            transition: var(--transition);
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-item:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        .activity-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: var(--white);
            background-color: var(--info);
            flex-shrink: 0;
            box-shadow: 0 3px 10px rgba(72, 149, 239, 0.3);
        }
        
        .activity-details {
            flex-grow: 1;
        }
        
        .activity-details h4 {
            margin: 0 0 0.25rem;
            font-size: 1rem;
            font-weight: 500;
        }
        
        .activity-details p {
            margin: 0;
            font-size: 0.85rem;
            color: var(--gray);
        }
        
        .activity-time {
            margin-left: 1rem;
            font-size: 0.8rem;
            color: var(--gray);
            white-space: nowrap;
        }
        
        .no-activity {
            padding: 2rem 0;
            text-align: center;
            color: var(--gray);
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .dashboard-container {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                display: none;
            }
        }
        
        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .main-content {
                padding: 1.5rem;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .stat-card, .activity-item {
            animation: fadeIn 0.5s ease forwards;
        }
        
        .activity-item:nth-child(1) { animation-delay: 0.1s; }
        .activity-item:nth-child(2) { animation-delay: 0.2s; }
        .activity-item:nth-child(3) { animation-delay: 0.3s; }
        .activity-item:nth-child(4) { animation-delay: 0.4s; }
        .activity-item:nth-child(5) { animation-delay: 0.5s; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <?php if (!empty($admin['profile_image'])): ?>
                    <img src="<?php echo htmlspecialchars($admin['profile_image']); ?>" alt="Admin Profile">
                <?php else: ?>
                    <div class="avatar-initials">
                        <?php echo strtoupper(substr($admin['first_name'], 0, 1) . substr($admin['last_name'], 0, 1)); ?>
                    </div>
                <?php endif; ?>
                <h3><?php echo htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']); ?></h3>
                <p><?php echo htmlspecialchars($admin['is_superadmin'] ? 'Super Admin' : 'Admin'); ?></p>
            </div>
            
            <div class="sidebar-menu">
                <a href="admin-dashboard.php" class="active">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="riders.php">
                    <i class="fas fa-motorcycle"></i> Riders
                </a>
                <a href="payments.php">
                    <i class="fas fa-money-bill-wave"></i> Payments
                </a>
                <a href="stickers.php">
                    <i class="fas fa-sticky-note"></i> Stickers
                </a>
                <?php if ($admin['is_superadmin']): ?>
                    <a href="admins.php">
                        <i class="fas fa-users-cog"></i> Manage Admins
                    </a>
                <?php endif; ?>
                <a href="reports.php">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
                <a href="settings.php">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Dashboard Overview</h1>
                <form action="logout.php" method="post">
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
            
            <!-- Stats Cards -->
            <div class="stats-container">
                <div class="stat-card riders">
                    <div class="stat-icon riders">
                        <i class="fas fa-motorcycle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Riders</h3>
                        <p><?php echo $total_riders; ?></p>
                    </div>
                </div>
                
                <div class="stat-card pending">
                    <div class="stat-icon pending">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Pending Approvals</h3>
                        <p><?php echo $pending_approvals; ?></p>
                    </div>
                </div>
                
                <div class="stat-card payments">
                    <div class="stat-icon payments">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Payments</h3>
                        <p><?php echo $total_payments; ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-history"></i> Recent Activity</h2>
                </div>
                <ul class="activity-list">
                    <?php if (!empty($recent_activities)): ?>
                        <?php foreach ($recent_activities as $activity): ?>
                            <li class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-<?php echo strpos($activity['activity_text'], 'login') !== false ? 'sign-in-alt' : 'history'; ?>"></i>
                                </div>
                                <div class="activity-details">
                                    <h4><?php echo htmlspecialchars($activity['activity_text']); ?></h4>
                                    <p>IP: <?php echo htmlspecialchars($activity['ip_address'] ?? 'N/A'); ?></p>
                                </div>
                                <div class="activity-time">
                                    <?php echo date('M j, g:i a', strtotime($activity['activity_date'])); ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php elseif ($table_result->num_rows > 0 && isset($date_column_result) && $date_column_result->num_rows > 0): ?>
                        <li class="activity-item">
                            <div class="no-activity">
                                <h4>No recent activity found</h4>
                            </div>
                        </li>
                    <?php else: ?>
                        <li class="activity-item">
                            <div class="no-activity">
                                <h4>Activity tracking not available</h4>
                                <p>System is not configured to track admin activities</p>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>