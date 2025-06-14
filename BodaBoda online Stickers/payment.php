<?php
// Start session
session_start();

// Include database connection
require_once 'db-conn.php';

// Process payment if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = $_POST['phone'];
    $amount = $_POST['amount'];
    $reference = 'PAY_' . uniqid();
    
    // Validate inputs
    if (!preg_match('/^[0-9]{10,12}$/', $phone)) {
        $error = "Invalid phone number format";
    } elseif (!is_numeric($amount) || $amount <= 0) {
        $error = "Invalid amount";
    } else {
        try {
            // Save to database
            $stmt = $conn->prepare("INSERT INTO payments (phone, amount, reference, status, created_at) 
                                   VALUES (:phone, :amount, :reference, 'pending', NOW())");
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':reference', $reference);
            $stmt->execute();
            
            // Set success message
            $_SESSION['success'] = "Payment request sent to $phone. Please complete the transaction on your phone.";
            header("Location: payment.php");
            exit();
            
        } catch(PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

// Display success message if exists
$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
unset($_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M-Pesa Payment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .mpesa-icon {
            color: #00B300;
            font-size: 50px;
            text-align: center;
            margin: 20px 0;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #00B300;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        button:hover {
            background-color: #009900;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .success {
            color: green;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="mpesa-icon">
            <i class="fas fa-mobile-alt"></i>
        </div>
        <h1>M-Pesa Payment</h1>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="payment.php">
            <div class="form-group">
                <label for="phone">Phone Number (e.g., 254712345678)</label>
                <input type="text" id="phone" name="phone" required placeholder="254712345678">
            </div>
            
            <div class="form-group">
                <label for="amount">Amount (KES)</label>
                <input type="number" id="amount" name="amount" required min="1">
            </div>
            
            <button type="submit">Pay via M-Pesa</button>
        </form>
    </div>
</body>
</html>