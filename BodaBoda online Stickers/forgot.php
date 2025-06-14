<?php
include('db-conn.php');

$error = '';
$success = '';
$show_form = true;

// [Previous PHP code remains exactly the same until the HTML part]
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - BODABODA ONLINE STICKERS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4CAF50;
            --primary-dark: #45a049;
            --error: #f44336;
            --success: #4CAF50;
            --text: #333;
            --light-text: #666;
            --border: #ddd;
            --bg: #f8f9fa;
            --card-bg: #fff;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        
        .container {
            width: 100%;
            max-width: 500px;
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo img {
            height: 60px;
        }
        
        h2 {
            text-align: center;
            color: var(--text);
            margin-bottom: 30px;
            font-weight: 600;
            font-size: 24px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text);
            font-size: 14px;
        }
        
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
            background-color: #f9f9f9;
        }
        
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
            background-color: #fff;
        }
        
        button {
            background-color: var(--primary);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }
        
        .error {
            color: var(--error);
            margin-bottom: 25px;
            padding: 15px;
            background-color: rgba(244, 67, 54, 0.1);
            border-radius: 8px;
            border-left: 4px solid var(--error);
            font-size: 14px;
            animation: shake 0.5s;
        }
        
        .success {
            color: var(--success);
            margin-bottom: 25px;
            padding: 15px;
            background-color: rgba(76, 175, 80, 0.1);
            border-radius: 8px;
            border-left: 4px solid var(--success);
            font-size: 14px;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }
        
        .login-link {
            text-align: center;
            margin-top: 25px;
            color: var(--light-text);
            font-size: 14px;
        }
        
        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .requirements {
            font-size: 12px;
            color: var(--light-text);
            margin-top: 6px;
            font-style: italic;
        }
        
        .password-container {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--light-text);
            font-size: 18px;
        }
        
        @media (max-width: 576px) {
            .container {
                padding: 30px 20px;
            }
            
            h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <!-- Add your logo here -->
            <!-- <img src="logo.png" alt="BodaBoda Stickers"> -->
            <h2>BODABODA ONLINE STICKERS</h2>
        </div>
        
        <h2>Password Recovery</h2>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($show_form && !isset($_GET['token'])): ?>
            <form action="forgot.php" method="POST">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email address" required>
                    <div class="requirements">We'll send a password reset link to this email</div>
                </div>
                <button type="submit">Send Reset Link</button>
            </form>
            <div class="login-link">
                Remember your password? <a href="login.php">Login here</a>
            </div>
        <?php elseif (!$show_form && isset($_GET['token'])): ?>
            <form action="forgot.php?token=<?php echo htmlspecialchars($_GET['token']); ?>" method="POST">
                <div class="form-group password-container">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter new password" required>
                    <div class="requirements">Minimum 8 characters</div>
                </div>
                <div class="form-group password-container">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required>
                </div>
                <button type="submit">Reset Password</button>
            </form>
        <?php endif; ?>
    </div>

    <script>
        // Toggle password visibility
        document.addEventListener('DOMContentLoaded', function() {
            const passwordFields = document.querySelectorAll('input[type="password"]');
            
            passwordFields.forEach(field => {
                const toggle = document.createElement('span');
                toggle.className = 'toggle-password';
                toggle.innerHTML = '👁️';
                toggle.addEventListener('click', function() {
                    const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
                    field.setAttribute('type', type);
                    this.innerHTML = type === 'password' ? '👁️' : '👁️‍🗨️';
                });
                field.parentNode.appendChild(toggle);
            });
        });
    </script>
</body>
</html>