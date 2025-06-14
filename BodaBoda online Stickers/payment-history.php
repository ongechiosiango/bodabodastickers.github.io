<?php
session_start();
require_once 'db-conn.php';

// Check if user is logged in (add your authentication logic here)
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }

// Fetch all payments from database
$payments = [];
$sql = "SELECT * FROM payments ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .header {
            background-color: #00B300;
            color: white;
            padding: 15px 0;
            margin-bottom: 30px;
        }
        .payment-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border-left: 4px solid #00B300;
        }
        .status-pending {
            color: #ffc107;
        }
        .status-completed {
            color: #28a745;
        }
        .status-failed {
            color: #dc3545;
        }
        .mpesa-icon {
            color: #00B300;
            font-size: 24px;
            margin-right: 10px;
        }
        .no-payments {
            text-align: center;
            padding: 50px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h1><i class="fas fa-history me-2"></i>Payment History</h1>
                <a href="payment.php" class="btn btn-light">
                    <i class="fas fa-arrow-left me-1"></i> Back to Payment
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if (!empty($payments)): ?>
            <div class="row">
                <?php foreach ($payments as $payment): ?>
                    <div class="col-md-6">
                        <div class="payment-card card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="card-title">
                                            <i class="fas fa-mobile-alt mpesa-icon"></i>
                                            <?php echo htmlspecialchars($payment['phone']); ?>
                                        </h5>
                                        <p class="card-text mb-1">
                                            <strong>Amount:</strong> KES <?php echo number_format($payment['amount'], 2); ?>
                                        </p>
                                        <p class="card-text mb-1">
                                            <strong>Reference:</strong> <?php echo htmlspecialchars($payment['reference']); ?>
                                        </p>
                                        <p class="card-text mb-1">
                                            <strong>Date:</strong> <?php echo date('d M Y H:i', strtotime($payment['created_at'])); ?>
                                        </p>
                                    </div>
                                    <div>
                                        <span class="badge rounded-pill status-<?php echo strtolower($payment['status']); ?>">
                                            <?php echo ucfirst($payment['status']); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-payments">
                <i class="fas fa-money-bill-wave fa-4x mb-3" style="color: #00B300;"></i>
                <h3>No Payment Records Found</h3>
                <p>You haven't made any payments yet.</p>
                <a href="payment.php" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i> Make Payment
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>