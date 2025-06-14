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

// Get all riders
$riders_sql = "SELECT * FROM riders ORDER BY registration_date DESC";
$riders_result = $conn->query($riders_sql);
$riders = $riders_result->fetch_all(MYSQLI_ASSOC);

// Count riders by status
$status_counts_sql = "SELECT 
                      SUM(CASE WHEN approved = 1 THEN 1 ELSE 0 END) as approved,
                      SUM(CASE WHEN approved = 0 THEN 1 ELSE 0 END) as pending,
                      COUNT(*) as total
                      FROM riders";
$status_result = $conn->query($status_counts_sql);
$status_counts = $status_result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riders - BODABODA ONLINE STICKERS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Use the same CSS as in your dashboard.php */
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
        
        /* ... (Include all the CSS from your dashboard.php) ... */
        
        /* Additional styles for riders page */
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }
        
        .badge-approved {
            background-color: rgba(76, 201, 240, 0.2);
            color: var(--success);
        }
        
        .badge-pending {
            background-color: rgba(248, 150, 30, 0.2);
            color: var(--warning);
        }
        
        .action-btn {
            padding: 0.25rem 0.5rem;
            border-radius: var(--border-radius);
            font-size: 0.8rem;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            margin-right: 0.25rem;
        }
        
        .btn-approve {
            background-color: var(--success);
            color: white;
        }
        
        .btn-reject {
            background-color: var(--danger);
            color: white;
        }
        
        .btn-view {
            background-color: var(--info);
            color: white;
        }
        
        .search-filter {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .search-filter input {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--gray-light);
            border-radius: var(--border-radius);
            flex-grow: 1;
        }
        
        .search-filter select {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--gray-light);
            border-radius: var(--border-radius);
            min-width: 150px;
        }
        
        .info-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .info-card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            padding: 1rem;
            box-shadow: var(--box-shadow);
        }
        
        .info-card h3 {
            margin-top: 0;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: var(--gray);
        }
        
        .info-card p {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar (same as dashboard.php) -->
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
                <a href="admin-dashboard.php">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="riders.php" class="active">
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
                <h1><i class="fas fa-motorcycle"></i> Rider Management</h1>
                <form action="logout.php" method="post">
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
            
            <!-- Quick Stats Cards -->
            <div class="info-cards">
                <div class="info-card">
                    <h3>Total Riders</h3>
                    <p><?php echo $status_counts['total']; ?></p>
                </div>
                <div class="info-card">
                    <h3>Approved Riders</h3>
                    <p><?php echo $status_counts['approved']; ?></p>
                </div>
                <div class="info-card">
                    <h3>Pending Approval</h3>
                    <p><?php echo $status_counts['pending']; ?></p>
                </div>
                <div class="info-card">
                    <h3>New This Month</h3>
                    <p><?php 
                        $month_sql = "SELECT COUNT(*) as count FROM riders 
                                     WHERE registration_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
                        $month_result = $conn->query($month_sql);
                        echo $month_result->fetch_assoc()['count'];
                    ?></p>
                </div>
            </div>
            
            <!-- Search and Filter -->
            <div class="search-filter">
                <input type="text" placeholder="Search riders...">
                <select>
                    <option value="">All Status</option>
                    <option value="approved">Approved</option>
                    <option value="pending">Pending</option>
                </select>
                <select>
                    <option value="">Sort by Date</option>
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                </select>
            </div>
            
            <!-- Information Boxes -->
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-info-circle"></i> Rider Information</h2>
                </div>
                <div style="padding: 1.5rem;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div>
                            <h3 style="margin-top: 0; color: var(--primary);">Approval Process</h3>
                            <p>All new riders must be approved before they can receive their stickers. Please verify:</p>
                            <ul style="padding-left: 1.5rem; margin: 0.5rem 0;">
                                <li>Valid identification documents</li>
                                <li>Completed registration form</li>
                                <li>Payment confirmation</li>
                                <li>Bike registration details</li>
                            </ul>
                        </div>
                        <div>
                            <h3 style="margin-top: 0; color: var(--primary);">Quick Actions</h3>
                            <p>Common tasks you can perform:</p>
                            <ul style="padding-left: 1.5rem; margin: 0.5rem 0;">
                                <li>Approve pending applications</li>
                                <li>View rider details</li>
                                <li>Resend approval notifications</li>
                                <li>Export rider data</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Riders Table -->
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-list"></i> All Riders</h2>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Bike No.</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($riders as $rider): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($rider['rider_id']); ?></td>
                                <td><?php echo htmlspecialchars($rider['first_name'] . ' ' . $rider['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($rider['phone_number']); ?></td>
                                <td><?php echo htmlspecialchars($rider['bike_registration'] ?? 'N/A'); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $rider['approved'] ? 'badge-approved' : 'badge-pending'; ?>">
                                        <?php echo $rider['approved'] ? 'Approved' : 'Pending'; ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($rider['registration_date'])); ?></td>
                                <td>
                                    <?php if (!$rider['approved']): ?>
                                        <button class="action-btn btn-approve">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    <?php endif; ?>
                                    <button class="action-btn btn-view">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
        // Simple search functionality
        document.querySelector('.search-filter input').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('.data-table tbody tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
        
        // Status filter
        document.querySelector('.search-filter select:nth-of-type(1)').addEventListener('change', function(e) {
            const status = e.target.value;
            document.querySelectorAll('.data-table tbody tr').forEach(row => {
                if (!status) {
                    row.style.display = '';
                    return;
                }
                
                const rowStatus = row.querySelector('.status-badge').textContent.toLowerCase();
                row.style.display = rowStatus.includes(status) ? '' : 'none';
            });
        });
        
        // Sort by date
        document.querySelector('.search-filter select:nth-of-type(2)').addEventListener('change', function(e) {
            const sortOrder = e.target.value;
            if (!sortOrder) return;
            
            const tbody = document.querySelector('.data-table tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            rows.sort((a, b) => {
                const dateA = new Date(a.cells[5].textContent);
                const dateB = new Date(b.cells[5].textContent);
                return sortOrder === 'newest' ? dateB - dateA : dateA - dateB;
            });
            
            // Re-add rows in sorted order
            rows.forEach(row => tbody.appendChild(row));
        });
    </script>
</body>
</html>