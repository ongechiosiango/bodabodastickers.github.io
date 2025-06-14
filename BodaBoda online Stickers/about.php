<?php include('db-conn.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - BODABODA ONLINE STICKERS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #333;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
        }
        .content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        .team {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-top: 30px;
        }
        .team-member {
            width: 30%;
            margin-bottom: 20px;
            text-align: center;
        }
        .team-member img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
        footer {
            text-align: center;
            padding: 20px 0;
            background-color: #333;
            color: white;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h2>BODABODA ONLINE STICKERS</h2>
        <div>
            <a href="index.php">Home</a>
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
            <a href="admin-login.php">Admin</a>
            <a href="about.php">About Us</a>
        </div>
    </header>
    
    <div class="container">
        <div class="content">
            <h1>About Us</h1>
            <p>Welcome to BODABODA ONLINE STICKERS, the premier platform for managing and issuing bodaboda stickers in Kenya. Our mission is to streamline the process of sticker registration and payment, making it convenient for both riders and administrators.</p>
            
            <h2>Our Services</h2>
            <ul>
                <li>Online sticker registration and payment</li>
                <li>Secure user accounts with profile management</li>
                <li>M-Pesa integration for seamless payments</li>
                <li>Automated sticker generation</li>
                <li>Admin dashboard for system management</li>
            </ul>
            
            <h2>Our Team</h2>
            <div class="team">
                <div class="team-member">
                    <img src="caleb-osiango.jpg" alt="Caleb Osiango">
                    <h3>Caleb Osiango</h3>
                    <p>Founder & CEO</p>
                </div>
                <div class="team-member">
                    <img src="kefah.jpg" alt="Kefah">
                    <h3>Kefah</h3>
                    <p>ICT NYAMIRA COUNTY</p>
                </div>
                <div class="team-member">
                    <img src="ongechi-osiango.jpg" alt="Ongechi Osiango">
                    <h3>Ongechi Osiango</h3>
                    <p>Customer Support</p>
                </div>
            </div>
            
            <h2>Contact Us</h2>
            <p>Email: calebosiango5@gmail.com</p>
            <p>Phone: +254 746327970</p>
            <p>Address: Kisii, Kenya</p>
        </div>
    </div>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> BODABODA ONLINE STICKERS. All rights reserved.</p>
    </footer>
</body>
</html>