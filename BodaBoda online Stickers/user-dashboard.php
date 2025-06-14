<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('db-conn.php');

// Get complete user data
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Calculate membership duration
$member_since = new DateTime($user['registration_date']);
$current_date = new DateTime();
$membership_duration = $current_date->diff($member_since)->format('%y years, %m months');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - BODABODA ONLINE STICKERS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --accent-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }
        
        .dashboard-container {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            background-color: var(--primary-color);
            color: white;
            padding: 20px 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        
        .sidebar-header h2 {
            margin: 10px 0 0;
            font-size: 1.2rem;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .menu-item:hover, .menu-item.active {
            background-color: rgba(255,255,255,0.1);
            border-left: 3px solid var(--accent-color);
        }
        
        .menu-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content Styles */
        .main-content {
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        
        .welcome-section {
            background-color: white;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .profile-card {
            background-color: white;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .profile-details div {
            margin-bottom: 12px;
        }
        
        .profile-details strong {
            display: inline-block;
            width: 120px;
            color: var(--dark-color);
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .action-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .action-card i {
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 15px;
        }
        
        .action-card h3 {
            margin: 0 0 10px;
            color: var(--primary-color);
        }
        
        .action-card p {
            color: #666;
            margin-bottom: 15px;
        }
        
        .btn {
            display: inline-block;
            padding: 8px 15px;
            background-color: var(--accent-color);
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #2980b9;
        }
        
        .btn-edit {
            background-color: var(--warning-color);
        }
        
        .btn-edit:hover {
            background-color: #e67e22;
        }
        
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 0.9rem;
        }
        
        .last-login {
            font-style: italic;
            color: #666;
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <div class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-motorcycle fa-3x"></i>
                <h2>BODABODA STICKERS</h2>
            </div>
            
            <div class="sidebar-menu">
                <a href="user-dashboard.php" class="menu-item active">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="user-profile.php" class="menu-item">
                    <i class="fas fa-user"></i> User Profile
                </a>
                <a href="payment-details.php" class="menu-item">
                    <i class="fas fa-credit-card"></i> Payment Details
                </a>
                <a href="payment-confirm.php" class="menu-item">
                    <i class="fas fa-check-circle"></i> Payment Confirm
                </a>
                <a href="sticker-sign.php" class="menu-item">
                    <i class="fas fa-signature"></i> Sign
                </a>
                <a href="sticker-print.php" class="menu-item">
                    <i class="fas fa-print"></i> Sticker Print
                </a>
                <a href="logout.php" class="menu-item">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>User Dashboard</h1>
                <a href="logout.php" class="btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
            
            <!-- Welcome Section -->
            <div class="welcome-section">
                <h2>Welcome Back, <?php echo htmlspecialchars($user['username']); ?></h2>
                <p>Here's your dashboard overview. You can manage your account and access all features from here.</p>
                <p class="last-login">Last login: <?php echo date('F j, Y g:i a', strtotime($user['last_login'])); ?></p>
            </div>
            
            <!-- User Profile Card -->
            <div class="profile-card">
                <div class="profile-header">
                    <h2><i class="fas fa-id-card"></i> User Profile</h2>
                    <a href="edit-profile.php" class="btn btn-edit">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                </div>
                
                <div class="profile-details">
                    <div><strong>Name:</strong> <?php echo htmlspecialchars($user['full_name'] ?? 'Not set'); ?></div>
                    <div><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></div>
                    <div><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone_number'] ?? 'Not set'); ?></div>
                    <div><strong>Bike Reg:</strong> <?php echo htmlspecialchars($user['bike_registration'] ?? 'Not registered'); ?></div>
                    <div><strong>Member Since:</strong> <?php echo date('F j, Y', strtotime($user['registration_date'])); ?> (<?php echo $membership_duration; ?>)</div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions">
                <div class="action-card">
                    <i class="fas fa-credit-card"></i>
                    <h3>Make Payment</h3>
                    <p>Pay for your bodaboda sticker registration</p>
                    <a href="payment.php" class="btn">Go to Payment</a>
                </div>
                
                <div class="action-card">
                    <i class="fas fa-print"></i>
                    <h3>Print Sticker</h3>
                    <p>Print your bodaboda registration sticker</p>
                    <a href="sticker-print.php" class="btn">Print Now</a>
                </div>
                
                <div class="action-card">
                    <i class="fas fa-history"></i>
                    <h3>Payment History</h3>
                    <p>View your payment transaction history</p>
                    <a href="payment-history.php" class="btn">View History</a>
                </div>
            </div>
            
            <div class="footer">
                © <?php echo date('Y'); ?> Bodaboda Online Stickers System. All Rights Reserved.
            </div>
        </div>
    </div>
</body>
</html>