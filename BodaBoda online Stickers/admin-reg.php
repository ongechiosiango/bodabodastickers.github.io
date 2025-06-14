<?php 
include('db-conn.php');
session_start();

// Redirect to dashboard if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: admin-dashboard.php");
    exit();
}

// Handle Registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    // Sanitize inputs
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $phone = preg_replace('/[^0-9]/', '', trim($_POST['phone']));
    
    // Standardize phone number format
    if (strlen($phone) == 10 && $phone[0] == '0') {
        $phone = '254' . substr($phone, 1);
    }

    // Validate inputs
    $errors = [];
    
    // Validate username (4-20 chars, alphanumeric)
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (!preg_match('/^[a-zA-Z0-9]{4,20}$/', $username)) {
        $errors[] = "Username must be 4-20 alphanumeric characters";
    }
    
    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    // Validate phone (Kenyan format)
    if (empty($phone)) {
        $errors[] = "Phone number is required";
    } elseif (!preg_match('/^(254|0)[0-9]{9}$/', $phone)) {
        $errors[] = "Invalid Kenyan phone number format";
    }
    
    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters";
    } elseif ($password != $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    // Validate names
    if (empty($first_name)) {
        $errors[] = "First name is required";
    }
    if (empty($last_name)) {
        $errors[] = "Last name is required";
    }
    
    // If no validation errors, check for duplicates
    if (empty($errors)) {
        // Check for existing username or email
        $check_sql = "SELECT 
                        SUM(username = ?) AS username_exists,
                        SUM(email = ?) AS email_exists
                      FROM admins";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ss", $username, $email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['username_exists'] > 0) {
            $errors[] = "Username already exists";
        }
        if ($row['email_exists'] > 0) {
            $errors[] = "Email already exists";
        }
        
        // If no duplicates, proceed with registration
        if (empty($errors)) {
            try {
                // Insert into admins table
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $admin_sql = "INSERT INTO admins (
                                username, password, email, 
                                first_name, last_name, phone, created_at
                              ) VALUES (?, ?, ?, ?, ?, ?, NOW())";
                $admin_stmt = $conn->prepare($admin_sql);
                $admin_stmt->bind_param(
                    "ssssss", 
                    $username, $hashed_password, $email,
                    $first_name, $last_name, $phone
                );
                $admin_stmt->execute();
                
                $_SESSION['registration_success'] = "Admin registration successful! You can now login.";
                header("Location: admin-login.php");
                exit();
                
            } catch (mysqli_sql_exception $e) {
                error_log("Registration Error: " . $e->getMessage());
                
                // Parse specific duplicate errors
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    if (strpos($e->getMessage(), 'username') !== false) {
                        $errors[] = "Username already exists (database constraint)";
                    } elseif (strpos($e->getMessage(), 'email') !== false) {
                        $errors[] = "Email already exists (database constraint)";
                    } else {
                        $errors[] = "Duplicate entry detected in database";
                    }
                } else {
                    $errors[] = "Registration failed. Please try again.";
                }
            }
        }
    }
    
    // Store errors and form data in session
    $_SESSION['registration_errors'] = $errors;
    $_SESSION['form_data'] = [
        'username' => $username,
        'email' => $email,
        'phone' => $phone,
        'first_name' => $first_name,
        'last_name' => $last_name
    ];
    header("Location: admin-reg.php");
    exit();
}

// Retrieve errors and form data from session
$errors = $_SESSION['registration_errors'] ?? [];
$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['registration_errors']);
unset($_SESSION['form_data']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration - BODABODA ONLINE STICKERS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .registration-container {
            width: 100%;
            max-width: 500px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .registration-header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .registration-header h2 {
            margin: 0;
            font-size: 24px;
        }
        .registration-header p {
            margin: 5px 0 0;
            font-size: 14px;
            opacity: 0.8;
        }
        .registration-body {
            padding: 30px;
        }
        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        .form-group {
            flex: 1;
            margin-bottom: 0;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            border-color: #3498db;
            outline: none;
        }
        .error-container {
            margin-bottom: 15px;
        }
        .error {
            color: #e74c3c;
            padding: 10px;
            background-color: #fdecea;
            border-radius: 4px;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .btn-register {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-register:hover {
            background-color: #2980b9;
        }
        .registration-footer {
            text-align: center;
            padding: 15px;
            border-top: 1px solid #eee;
            font-size: 14px;
        }
        .registration-footer a {
            color: #3498db;
            text-decoration: none;
        }
        .registration-footer a:hover {
            text-decoration: underline;
        }
        .password-container {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #7f8c8d;
        }
        .input-error {
            border-color: #e74c3c !important;
        }
        .phone-hint {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 4px;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <div class="registration-header">
            <h2>Admin Registration</h2>
            <p>BodaBoda Online Stickers Management</p>
        </div>
        
        <div class="registration-body">
            <?php if (!empty($errors)): ?>
                <div class="error-container">
                    <?php foreach ($errors as $error): ?>
                        <div class="error">
                            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form action="admin-reg.php" method="POST" autocomplete="off">
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name *</label>
                        <input type="text" id="first_name" name="first_name" required
                               value="<?php echo htmlspecialchars($form_data['first_name'] ?? ''); ?>"
                               class="<?php echo (in_array('First name is required', $errors) ? 'input-error' : ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" required
                               value="<?php echo htmlspecialchars($form_data['last_name'] ?? ''); ?>"
                               class="<?php echo (in_array('Last name is required', $errors) ? 'input-error' : ''); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="username">Username *</label>
                    <input type="text" id="username" name="username" required
                           value="<?php echo htmlspecialchars($form_data['username'] ?? ''); ?>"
                           class="<?php echo (in_array('Username already exists', $errors) ? 'input-error' : ''); ?>"
                           pattern="[a-zA-Z0-9]{4,20}" 
                           title="4-20 alphanumeric characters">
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required
                           value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>"
                           class="<?php echo (in_array('Email already exists', $errors) ? 'input-error' : ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" required
                           value="<?php echo htmlspecialchars($form_data['phone'] ?? ''); ?>"
                           class="<?php echo (in_array('Invalid Kenyan phone number format', $errors) ? 'input-error' : ''); ?>"
                           pattern="(254|0)[0-9]{9}" 
                           title="Kenyan phone number (254xxxxxxxxx or 07xxxxxxxx)">
                    <div class="phone-hint">Format: 254XXXXXXXXX or 07XXXXXXXX</div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <div class="password-container">
                            <input type="password" id="password" name="password" required
                                   minlength="8"
                                   title="Password must be at least 8 characters">
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('password')"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password *</label>
                        <div class="password-container">
                            <input type="password" id="confirm_password" name="confirm_password" required
                                   minlength="8"
                                   title="Password must be at least 8 characters">
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('confirm_password')"></i>
                        </div>
                    </div>
                </div>
                
                <button type="submit" name="register" class="btn-register">
                    <i class="fas fa-user-plus"></i> Register
                </button>
            </form>
        </div>
        
        <div class="registration-footer">
            Already have an account? <a href="admin-login.php">Login here</a>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const toggleIcon = passwordField.nextElementSibling;
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
        
        // Highlight fields with errors on page load
        document.addEventListener('DOMContentLoaded', function() {
            const errorFields = document.querySelectorAll('.input-error');
            if (errorFields.length > 0) {
                errorFields[0].focus();
            }
            
            // Format phone number display
            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 0 && value[0] !== '0' && value.substring(0, 3) !== '254') {
                        value = '254' + value;
                    }
                    e.target.value = value;
                });
            }
        });
    </script>
</body>
</html>