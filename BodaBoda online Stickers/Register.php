<?php 
include('db-conn.php');

$error = '';
$success = '';

// Kenyan counties array
$kenyanCounties = [
    "Mombasa", "Kwale", "Kilifi", "Tana River", "Lamu", "Taita-Taveta", 
    "Garissa", "Wajir", "Mandera", "Marsabit", "Isiolo", "Meru", 
    "Tharaka-Nithi", "Embu", "Kitui", "Machakos", "Makueni", "Nyandarua", 
    "Nyeri", "Kirinyaga", "Murang'a", "Kiambu", "Turkana", "West Pokot", 
    "Samburu", "Trans Nzoia", "Uasin Gishu", "Elgeyo-Marakwet", "Nandi", 
    "Baringo", "Laikipia", "Nakuru", "Narok", "Kajiado", "Kericho", 
    "Bomet", "Kakamega", "Vihiga", "Bungoma", "Busia", "Siaya", 
    "Kisumu", "Homa Bay", "Migori", "Kisii", "Nyamira", "Nairobi"
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $age = $_POST['age'];
    $bike_reg = $_POST['bike_reg'];
    $county = $_POST['county'];
    $phone = $_POST['phone'];
    $id_number = $_POST['id_number'];
    
    // Validate inputs
    if (strlen($username) < 4 || strlen($username) > 20) {
        $error = "Username must be between 4-20 characters.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($age < 18) {
        $error = "You must be 18 years or older to register.";
    } elseif (!preg_match('/^\d{10}$/', $phone)) {
        $error = "Phone number must be 10 digits.";
    } elseif (!preg_match('/^\d{8}$/', $id_number)) {
        $error = "ID number must be 8 digits.";
    } elseif (!preg_match('/^[A-Za-z]{2,4}\s?\d{3,4}[A-Za-z]?$/', $bike_reg)) {
        $error = "Invalid bike registration format. Use format like KAA 123A or KAAA123B";
    } else {
        // Check if username already exists
        $check_sql = "SELECT username FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_stmt->store_result();
        
        if ($check_stmt->num_rows > 0) {
            $error = "Username already taken. Please choose another.";
        } else {
            // Check if email already exists
            $check_sql = "SELECT email FROM users WHERE email = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("s", $email);
            $check_stmt->execute();
            $check_stmt->store_result();
            
            if ($check_stmt->num_rows > 0) {
                $error = "Email already registered. Please use another email or login.";
            } else {
                // Check if bike registration exists
                $check_sql = "SELECT bike_registration FROM users WHERE bike_registration = ?";
                $check_stmt = $conn->prepare($check_sql);
                $check_stmt->bind_param("s", $bike_reg);
                $check_stmt->execute();
                $check_stmt->store_result();
                
                if ($check_stmt->num_rows > 0) {
                    $error = "Bike registration number already registered.";
                } else {
                    // Insert new user
                    $sql = "INSERT INTO users (username, email, password, age, bike_registration, county, phone_number, id_number) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssissss", $username, $email, $password, $age, $bike_reg, $county, $phone, $id_number);
                    
                    if ($stmt->execute()) {
                        $success = "Registration successful! You can now login.";
                        // Clear form fields after successful registration
                        $_POST = array();
                    } else {
                        $error = "Registration failed: " . $conn->error;
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - BODABODA ONLINE STICKERS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .input-card {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            border-left: 4px solid #4CAF50;
            height: 100%;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border 0.3s;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="number"]:focus,
        select:focus {
            border-color: #4CAF50;
            outline: none;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
            margin-top: 20px;
            grid-column: span 2;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #ffebee;
            border-radius: 8px;
            border-left: 4px solid #f44336;
            grid-column: span 2;
        }
        .success {
            color: green;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #e8f5e9;
            border-radius: 8px;
            border-left: 4px solid #4CAF50;
            grid-column: span 2;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            grid-column: span 2;
        }
        .login-link a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .requirements {
            font-size: 0.8em;
            color: #666;
            margin-top: 8px;
            font-style: italic;
        }
        
        /* Responsive layout */
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            button, .error, .success, .login-link {
                grid-column: span 1;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Registration</h2>
        
        <form action="register.php" method="POST" onsubmit="return validateForm()">
            <div class="form-grid">
                <?php if (!empty($error)): ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if (!empty($success)): ?>
                    <div class="success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <div class="form-group">
                    <div class="input-card">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                        <div class="requirements">Must be unique, 4-20 characters</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="input-card">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="input-card">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                        <div class="requirements">Minimum 8 characters</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="input-card">
                        <label for="age">Age:</label>
                        <input type="number" id="age" name="age" min="18" value="<?php echo isset($_POST['age']) ? htmlspecialchars($_POST['age']) : ''; ?>" required>
                        <div class="requirements">Must be 18 or older</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="input-card">
                        <label for="bike_reg">Bike Registration:</label>
                        <input type="text" id="bike_reg" name="bike_reg" value="<?php echo isset($_POST['bike_reg']) ? htmlspecialchars($_POST['bike_reg']) : ''; ?>" required>
                        <div class="requirements">Format: ABCD 123X or ABCD123X</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="input-card">
                        <label for="county">County:</label>
                        <select id="county" name="county" required>
                            <option value="">Select County</option>
                            <?php foreach ($kenyanCounties as $countyOption): ?>
                                <option value="<?php echo htmlspecialchars($countyOption); ?>" 
                                    <?php echo (isset($_POST['county']) && $_POST['county'] == $countyOption) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($countyOption); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="input-card">
                        <label for="phone">Phone Number:</label>
                        <input type="text" id="phone" name="phone" pattern="[0-9]{10}" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" required>
                        <div class="requirements">10 digits (e.g., 0712345678)</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="input-card">
                        <label for="id_number">ID Number:</label>
                        <input type="text" id="id_number" name="id_number" value="<?php echo isset($_POST['id_number']) ? htmlspecialchars($_POST['id_number']) : ''; ?>" required>
                        <div class="requirements">8 digits (e.g., 12345678)</div>
                    </div>
                </div>
                
                <button type="submit">Register</button>
                
                <div class="login-link">
                    Already have an account? <a href="login.php">Login here</a>
                </div>
            </div>
        </form>
    </div>

    <script>
        function validateForm() {
            // Client-side validation
            const username = document.getElementById('username').value;
            if (username.length < 4 || username.length > 20) {
                alert('Username must be between 4-20 characters');
                return false;
            }
            
            const email = document.getElementById('email').value;
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                alert('Please enter a valid email address');
                return false;
            }
            
            const password = document.getElementById('password').value;
            if (password.length < 8) {
                alert('Password must be at least 8 characters');
                return false;
            }
            
            const age = document.getElementById('age').value;
            if (age < 18) {
                alert('You must be 18 years or older to register');
                return false;
            }
            
            const phone = document.getElementById('phone').value;
            if (!/^\d{10}$/.test(phone)) {
                alert('Please enter a valid 10-digit phone number');
                return false;
            }
            
            const idNumber = document.getElementById('id_number').value;
            if (!/^\d{8}$/.test(idNumber)) {
                alert('Please enter a valid 8-digit ID number');
                return false;
            }
            
            const bikeReg = document.getElementById('bike_reg').value;
            if (!/^[A-Za-z]{2,4}\s?\d{3,4}[A-Za-z]?$/.test(bikeReg)) {
                alert('Please enter a valid bike registration (e.g., KAA 123A or KAAA123B)');
                return false;
            }
            
            return true;
        }
    </script>
</body>
</html>