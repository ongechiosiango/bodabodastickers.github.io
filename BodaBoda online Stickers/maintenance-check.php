<?php 
include('db-conn.php');

// Check if user is logged in as admin
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

// Process form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $boda_id = $_POST['boda_id'];
    $check_date = $_POST['check_date'];
    $next_check_date = $_POST['next_check_date'];
    $issues_found = $_POST['issues_found'];
    $action_taken = $_POST['action_taken'];
    $mechanic_notes = $_POST['mechanic_notes'];
    $status = $_POST['status'];
    
    $stmt = $conn->prepare("INSERT INTO maintenance_checks (boda_id, check_date, next_check_date, issues_found, action_taken, mechanic_notes, status) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $boda_id, $check_date, $next_check_date, $issues_found, $action_taken, $mechanic_notes, $status);
    
    if($stmt->execute()) {
        $success = "Maintenance record added successfully!";
    } else {
        $error = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Get list of registered bodas for dropdown
$boda_list = $conn->query("SELECT id, rider_name, registration_number FROM bodaboda_registrations");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BodaBoda Maintenance Check System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #333;
            color: white;
            padding: 15px 0;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        textarea {
            height: 100px;
            resize: vertical;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .alert-error {
            background-color: #f2dede;
            color: #a94442;
        }
        .maintenance-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        .maintenance-table th, .maintenance-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .maintenance-table th {
            background-color: #333;
            color: white;
        }
        .maintenance-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .status-pending {
            color: #e67e22;
            font-weight: bold;
        }
        .status-completed {
            color: #27ae60;
            font-weight: bold;
        }
        .status-overdue {
            color: #e74c3c;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-tools"></i> BodaBoda Maintenance Check System</h1>
    </div>
    
    <div class="container">
        <?php if(isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="form-container">
            <h2><i class="fas fa-plus-circle"></i> Add New Maintenance Check</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="boda_id">Select BodaBoda:</label>
                    <select name="boda_id" id="boda_id" required>
                        <option value="">-- Select BodaBoda --</option>
                        <?php while($boda = $boda_list->fetch_assoc()): ?>
                            <option value="<?php echo $boda['id']; ?>">
                                <?php echo $boda['registration_number'] . " - " . $boda['rider_name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="check_date">Check Date:</label>
                    <input type="date" name="check_date" id="check_date" required>
                </div>
                
                <div class="form-group">
                    <label for="next_check_date">Next Check Date:</label>
                    <input type="date" name="next_check_date" id="next_check_date" required>
                </div>
                
                <div class="form-group">
                    <label for="issues_found">Issues Found:</label>
                    <textarea name="issues_found" id="issues_found" placeholder="Describe any issues found during maintenance"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="action_taken">Action Taken:</label>
                    <textarea name="action_taken" id="action_taken" placeholder="Describe the maintenance actions taken"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="mechanic_notes">Mechanic Notes:</label>
                    <textarea name="mechanic_notes" id="mechanic_notes" placeholder="Any additional notes from the mechanic"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="status">Maintenance Status:</label>
                    <select name="status" id="status" required>
                        <option value="pending">Pending</option>
                        <option value="completed" selected>Completed</option>
                        <option value="overdue">Overdue</option>
                    </select>
                </div>
                
                <button type="submit" class="btn"><i class="fas fa-save"></i> Save Maintenance Record</button>
            </form>
        </div>
        
        <div class="maintenance-records">
            <h2><i class="fas fa-list"></i> Recent Maintenance Records</h2>
            <?php
            $records = $conn->query("SELECT m.*, b.registration_number, b.rider_name 
                                   FROM maintenance_checks m
                                   JOIN bodaboda_registrations b ON m.boda_id = b.id
                                   ORDER BY m.check_date DESC LIMIT 10");
            
            if($records->num_rows > 0): ?>
                <table class="maintenance-table">
                    <thead>
                        <tr>
                            <th>Boda No.</th>
                            <th>Rider Name</th>
                            <th>Check Date</th>
                            <th>Next Check</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($record = $records->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $record['registration_number']; ?></td>
                                <td><?php echo $record['rider_name']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($record['check_date'])); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($record['next_check_date'])); ?></td>
                                <td>
                                    <span class="status-<?php echo $record['status']; ?>">
                                        <?php echo ucfirst($record['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="maintenance-details.php?id=<?php echo $record['id']; ?>" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <p style="text-align: right; margin-top: 10px;">
                    <a href="all-maintenance.php">View All Maintenance Records →</a>
                </p>
            <?php else: ?>
                <p>No maintenance records found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>