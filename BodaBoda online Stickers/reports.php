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

// Date range for reports (default to current month)
$start_date = date('Y-m-01');
$end_date = date('Y-m-t');
$filter_applied = false;

// Process date filter form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filter'])) {
    $start_date = $_POST['start_date'] ?? date('Y-m-01');
    $end_date = $_POST['end_date'] ?? date('Y-m-t');
    $filter_applied = true;
}

// Get payment reports
$payment_sql = "SELECT DATE(payment_date) as day, COUNT(*) as count, SUM(amount) as total 
               FROM payments 
               WHERE payment_date BETWEEN ? AND ? 
               GROUP BY DATE(payment_date) 
               ORDER BY day DESC";
$payment_stmt = $conn->prepare($payment_sql);
$payment_stmt->bind_param("ss", $start_date, $end_date);
$payment_stmt->execute();
$payment_results = $payment_stmt->get_result();
$payments = $payment_results->fetch_all(MYSQLI_ASSOC);

// Get rider registration reports
$rider_sql = "SELECT DATE(registration_date) as day, COUNT(*) as count 
             FROM riders 
             WHERE registration_date BETWEEN ? AND ? 
             GROUP BY DATE(registration_date) 
             ORDER BY day DESC";
$rider_stmt = $conn->prepare($rider_sql);
$rider_stmt->bind_param("ss", $start_date, $end_date);
$rider_stmt->execute();
$rider_results = $rider_stmt->get_result();
$riders = $rider_results->fetch_all(MYSQLI_ASSOC);

// Calculate totals
$total_payments = array_sum(array_column($payments, 'total'));
$total_payment_count = array_sum(array_column($payments, 'count'));
$total_riders = array_sum(array_column($riders, 'count'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - BODABODA ONLINE STICKERS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        
        /* Filter Form */
        .filter-form {
            background-color: var(--white);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--box-shadow);
            margin-bottom: 2rem;
        }
        
        .filter-form h2 {
            margin-top: 0;
            margin-bottom: 1.5rem;
            font-size: 1.25rem;
            color: var(--primary);
        }
        
        .form-group {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            align-items: center;
        }
        
        .form-group label {
            font-weight: 500;
            color: var(--dark);
            min-width: 100px;
        }
        
        .form-group input {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--gray-light);
            border-radius: var(--border-radius);
            flex-grow: 1;
            max-width: 200px;
        }
        
        .filter-btn {
            background-color: var(--primary);
            color: var(--white);
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
        }
        
        .filter-btn:hover {
            background-color: var(--primary-dark);
        }
        
        .reset-btn {
            background-color: var(--gray-light);
            color: var(--dark);
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
            margin-left: 0.5rem;
        }
        
        .reset-btn:hover {
            background-color: #d1d7e0;
        }
        
        /* Summary Cards */
        .summary-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .summary-card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
        }
        
        .summary-card h3 {
            margin-top: 0;
            margin-bottom: 0.5rem;
            font-size: 1rem;
            font-weight: 500;
            color: var(--gray);
        }
        
        .summary-card p {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark);
        }
        
        .summary-card .trend {
            display: flex;
            align-items: center;
            margin-top: 0.5rem;
            font-size: 0.9rem;
        }
        
        .trend.up {
            color: var(--success);
        }
        
        .trend.down {
            color: var(--danger);
        }
        
        /* Charts */
        .chart-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .chart-card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--box-shadow);
        }
        
        .chart-card h2 {
            margin-top: 0;
            margin-bottom: 1.5rem;
            font-size: 1.25rem;
            color: var(--primary);
        }
        
        .chart-wrapper {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        /* Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
            background-color: var(--white);
            box-shadow: var(--box-shadow);
            border-radius: var(--border-radius);
            overflow: hidden;
        }
        
        .data-table th, .data-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--gray-light);
        }
        
        .data-table th {
            background-color: var(--primary);
            color: var(--white);
            font-weight: 500;
        }
        
        .data-table tr:last-child td {
            border-bottom: none;
        }
        
        .data-table tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
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
            .summary-container {
                grid-template-columns: 1fr;
            }
            
            .main-content {
                padding: 1.5rem;
            }
            
            .form-group {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .form-group input {
                max-width: 100%;
                width: 100%;
            }
        }
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
                <a href="admin-dashboard.php">
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
                <a href="reports.php" class="active">
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
                <h1><i class="fas fa-chart-bar"></i> Reports</h1>
                <form action="logout.php" method="post">
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
            
            <!-- Date Filter Form -->
            <form method="POST" class="filter-form">
                <h2><i class="fas fa-filter"></i> Filter Reports</h2>
                <div class="form-group">
                    <label for="start_date">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>" required>
                </div>
                <div class="form-group">
                    <label for="end_date">End Date:</label>
                    <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>" required>
                </div>
                <button type="submit" name="filter" class="filter-btn">
                    <i class="fas fa-filter"></i> Apply Filter
                </button>
                <a href="reports.php" class="reset-btn">
                    <i class="fas fa-sync-alt"></i> Reset
                </a>
                <?php if ($filter_applied): ?>
                    <p style="margin-top: 1rem; color: var(--gray);">
                        Showing reports from <?php echo date('M j, Y', strtotime($start_date)); ?> to <?php echo date('M j, Y', strtotime($end_date)); ?>
                    </p>
                <?php endif; ?>
            </form>
            
            <!-- Summary Cards -->
            <div class="summary-container">
                <div class="summary-card">
                    <h3>Total Payments</h3>
                    <p><?php echo number_format($total_payments, 2); ?></p>
                    <div class="trend up">
                        <i class="fas fa-arrow-up"></i> Total revenue
                    </div>
                </div>
                <div class="summary-card">
                    <h3>Payment Transactions</h3>
                    <p><?php echo $total_payment_count; ?></p>
                    <div class="trend up">
                        <i class="fas fa-arrow-up"></i> Total transactions
                    </div>
                </div>
                <div class="summary-card">
                    <h3>New Riders</h3>
                    <p><?php echo $total_riders; ?></p>
                    <div class="trend up">
                        <i class="fas fa-arrow-up"></i> New registrations
                    </div>
                </div>
            </div>
            
            <!-- Charts -->
            <div class="chart-container">
                <div class="chart-card">
                    <h2><i class="fas fa-money-bill-wave"></i> Payments Over Time</h2>
                    <div class="chart-wrapper">
                        <canvas id="paymentsChart"></canvas>
                    </div>
                </div>
                
                <div class="chart-card">
                    <h2><i class="fas fa-motorcycle"></i> Rider Registrations</h2>
                    <div class="chart-wrapper">
                        <canvas id="ridersChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Payment Data Table -->
            <div class="chart-card">
                <h2><i class="fas fa-table"></i> Payment Details</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Transactions</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($payments)): ?>
                            <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><?php echo date('M j, Y', strtotime($payment['day'])); ?></td>
                                    <td><?php echo $payment['count']; ?></td>
                                    <td><?php echo number_format($payment['total'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" style="text-align: center;">No payment data available for the selected period</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Rider Data Table -->
            <div class="chart-card">
                <h2><i class="fas fa-table"></i> Rider Registration Details</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Registrations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($riders)): ?>
                            <?php foreach ($riders as $rider): ?>
                                <tr>
                                    <td><?php echo date('M j, Y', strtotime($rider['day'])); ?></td>
                                    <td><?php echo $rider['count']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" style="text-align: center;">No rider registration data available for the selected period</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
        // Prepare data for charts
        const paymentDates = <?php echo json_encode(array_column($payments, 'day')); ?>;
        const paymentAmounts = <?php echo json_encode(array_column($payments, 'total')); ?>;
        const paymentCounts = <?php echo json_encode(array_column($payments, 'count')); ?>;
        
        const riderDates = <?php echo json_encode(array_column($riders, 'day')); ?>;
        const riderCounts = <?php echo json_encode(array_column($riders, 'count')); ?>;
        
        // Format dates for display
        const formattedPaymentDates = paymentDates.map(date => new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        const formattedRiderDates = riderDates.map(date => new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        
        // Payments Chart
        const paymentsCtx = document.getElementById('paymentsChart').getContext('2d');
        const paymentsChart = new Chart(paymentsCtx, {
            type: 'bar',
            data: {
                labels: formattedPaymentDates,
                datasets: [
                    {
                        label: 'Payment Amount',
                        data: paymentAmounts,
                        backgroundColor: 'rgba(67, 97, 238, 0.7)',
                        borderColor: 'rgba(67, 97, 238, 1)',
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Transaction Count',
                        data: paymentCounts,
                        backgroundColor: 'rgba(76, 201, 240, 0.7)',
                        borderColor: 'rgba(76, 201, 240, 1)',
                        borderWidth: 1,
                        type: 'line',
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Amount'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Count'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
        
        // Riders Chart
        const ridersCtx = document.getElementById('ridersChart').getContext('2d');
        const ridersChart = new Chart(ridersCtx, {
            type: 'line',
            data: {
                labels: formattedRiderDates,
                datasets: [{
                    label: 'Rider Registrations',
                    data: riderCounts,
                    backgroundColor: 'rgba(248, 150, 30, 0.2)',
                    borderColor: 'rgba(248, 150, 30, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Registrations'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>