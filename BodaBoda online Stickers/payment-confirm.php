<?php
session_start();
include('db-conn.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Initialize variables
$error = '';
$success = '';
$paymentData = [];

try {
    // Get the latest payment for this user
    $stmt = $conn->prepare("SELECT * FROM payments WHERE user_id = ? ORDER BY payment_date DESC LIMIT 1");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $paymentData = $result->fetch_assoc();
    $stmt->close();

    // Handle payment confirmation
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_payment'])) {
        if (!$paymentData) {
            throw new Exception("No payment found to confirm");
        }

        // Start transaction
        $conn->begin_transaction();

        try {
            // 1. Update payment status
            $stmt = $conn->prepare("UPDATE payments SET status = 'confirmed' WHERE payment_id = ?");
            $stmt->bind_param("i", $paymentData['payment_id']);
            $stmt->execute();
            $stmt->close();

            // 2. Create or update sticker
            $stickerCode = 'STK-' . strtoupper(substr(md5(uniqid()), 0, 8));
            
            // Check if sticker already exists
            $checkStmt = $conn->prepare("SELECT sticker_id FROM stickers WHERE payment_id = ?");
            $checkStmt->bind_param("i", $paymentData['payment_id']);
            $checkStmt->execute();
            $stickerExists = $checkStmt->get_result()->num_rows > 0;
            $checkStmt->close();

            if ($stickerExists) {
                $updateStmt = $conn->prepare("UPDATE stickers SET status = 'active', updated_at = NOW() WHERE payment_id = ?");
                $updateStmt->bind_param("i", $paymentData['payment_id']);
                $updateStmt->execute();
                $updateStmt->close();
            } else {
                $insertStmt = $conn->prepare("INSERT INTO stickers (user_id, payment_id, sticker_code, status) VALUES (?, ?, ?, 'active')");
                $insertStmt->bind_param("iis", $_SESSION['user_id'], $paymentData['payment_id'], $stickerCode);
                $insertStmt->execute();
                $insertStmt->close();
            }

            // Commit transaction
            $conn->commit();
            $success = "Payment confirmed successfully! Your sticker is now active.";

            // Refresh payment data
            $stmt = $conn->prepare("SELECT * FROM payments WHERE payment_id = ?");
            $stmt->bind_param("i", $paymentData['payment_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $paymentData = $result->fetch_assoc();
            $stmt->close();
        } catch (Exception $e) {
            $conn->rollback();
            throw $e;
        }
    }
} catch (Exception $e) {
    $error = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation - BODABODA ONLINE STICKERS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Your existing CSS styles remain the same */
        /* ... */
    </style>
</head>
<body>
    <header class="main-header no-print">
        <div class="header-content">
            <h1><i class="fas fa-motorcycle"></i> BODABODA ONLINE STICKERS</h1>
            <nav>
                <a href="user-dashboard.php" style="color: white;"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <h2><i class="fas fa-receipt"></i> Payment Confirmation</h2>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($paymentData): ?>
            <div class="payment-details">
                <div class="payment-item">
                    <span class="payment-label">Payment ID:</span>
                    <span class="payment-value"><?php echo htmlspecialchars($paymentData['payment_id']); ?></span>
                </div>
                
                <div class="payment-item">
                    <span class="payment-label">Amount Paid:</span>
                    <span class="payment-value">KSh <?php echo number_format($paymentData['amount'], 2); ?></span>
                </div>
                
                <div class="payment-item">
                    <span class="payment-label">Payment Method:</span>
                    <span class="payment-value"><?php 
                        echo htmlspecialchars(ucfirst(str_replace('_', ' ', $paymentData['payment_method'])));
                    ?></span>
                </div>
                
                <div class="payment-item">
                    <span class="payment-label">Transaction Code:</span>
                    <span class="payment-value"><?php 
                        echo $paymentData['transaction_code'] ? htmlspecialchars($paymentData['transaction_code']) : 'N/A';
                    ?></span>
                </div>
                
                <div class="payment-item">
                    <span class="payment-label">Payment Date:</span>
                    <span class="payment-value"><?php echo date('F j, Y g:i a', strtotime($paymentData['payment_date'])); ?></span>
                </div>
                
                <div class="payment-item">
                    <span class="payment-label">Status:</span>
                    <span class="payment-value payment-status status-<?php echo $paymentData['status']; ?>">
                        <?php echo htmlspecialchars(ucfirst($paymentData['status'])); ?>
                    </span>
                </div>
            </div>
            
            <div class="action-buttons no-print">
                <?php if ($paymentData['status'] == 'pending'): ?>
                    <form action="payment-confirm.php" method="POST">
                        <button type="submit" name="confirm_payment" class="btn">
                            <i class="fas fa-check-circle"></i> Confirm Payment
                        </button>
                    </form>
                <?php endif; ?>
                
                <button onclick="window.print()" class="btn btn-print">
                    <i class="fas fa-print"></i> Print Receipt
                </button>
                
                <a href="sticker-print.php" class="btn">
                    <i class="fas fa-id-card"></i> View Sticker
                </a>
            </div>
        <?php else: ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> No payment records found. Please make a payment first.
            </div>
            
            <div class="action-buttons no-print">
                <a href="payment.php" class="btn">
                    <i class="fas fa-credit-card"></i> Make Payment
                </a>
            </div>
        <?php endif; ?>
    </div>

    <footer class="main-footer no-print">
        <p>&copy; <?php echo date('Y'); ?> BODABODA ONLINE STICKERS. All rights reserved.</p>
    </footer>
</body>
</html>